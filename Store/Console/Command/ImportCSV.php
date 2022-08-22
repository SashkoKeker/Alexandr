<?php

namespace Alexandr\Store\Console\Command;

use Magento\Framework\File\Csv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Filesystem\DirectoryList;

use Alexandr\Store\Api\Data\StoreInterfaceFactory;
use Alexandr\Store\Api\StoreRepositoryInterface;
use Alexandr\Store\Api\GeoCoderInterface;

class ImportCSV extends Command
{

    const NAME = 'file';

    const PATH = 'path';

    /**
     * @var State
     */
    protected State $state;
    /**
     * @var StoreInterfaceFactory
     */
    protected StoreInterfaceFactory $storeFactory;
    /**
     * @var Csv
     */
    protected Csv $scv;
    /**
     * @var DirectoryList
     */
    protected DirectoryList $directoryList;
    /**
     * @var GeoCoderInterface
     */
    protected GeoCoderInterface $geoCoder;
    /**
     * @var StoreRepositoryInterface
     */
    protected StoreRepositoryInterface $storeRepository;

    /**
     * @param State $state
     * @param DirectoryList $directoryList
     * @param StoreInterfaceFactory $storeInterfaceFactory
     * @param Csv $csv
     * @param GeoCoderInterface $geoCoder
     * @param StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        State $state,
        DirectoryList $directoryList,
        StoreInterfaceFactory $storeInterfaceFactory,
        Csv $csv,
        GeoCoderInterface $geoCoder,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->geoCoder = $geoCoder;
        $this->storeRepository = $storeRepository;
        $this->directoryList = $directoryList;
        $this->scv = $csv;
        $this->storeFactory = $storeInterfaceFactory;
        $this->state = $state;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('import:store:csv');
        $this->setDescription('Import stores from csv to DB');
        $this->addOption(
            self::NAME,
            null,
            InputOption::VALUE_REQUIRED,
            'File name'
        );
        $this->addOption(
            self::PATH,
            null,
            InputOption::VALUE_OPTIONAL,
            'Path to file'
        );

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $filename = $input->getOption(self::NAME);
        $filepath = $input->getOption(self::PATH);


        $this->importCSV($filename, $filepath);
    }

    /**
     * @param $filename
     * @param $filepath
     * @return void
     */
    public function importCSV(
        $filename,
        $filepath
    ){
        $file = $filepath . $filename;
        $csvData = fopen($file, 'r');

        $keys = fgetcsv($csvData);
        while ($row = fgetcsv($csvData)) {
            $store = $this->storeFactory->create();
            $data = array_combine($keys, $row);
            if (!empty($data['position'])) {
                $coordinates = expode(",", $data['position']);
            } else {
                $address = $data['address'];
                $coordinates = $this->geoCoder->getCoordinatesByAddress($address);
            }

            $store->setLatitude($coordinates[1]);
            $store->setLongitude($coordinates[0]);
            $store->setName($data['name']);
            $store->setAddress($data['country'] .', ' . $data['city'] . ', ' . $data['address']);
            $store->setDescription('Phone: ' . $data['phone']);
            $this->storeRepository->save($store);
            $store->setUrl(str_replace(' ', '', strtolower($data['name'])) . '+' . $store->getId());
            $this->storeRepository->save($store);
            unset($store);

        }
    }
}
