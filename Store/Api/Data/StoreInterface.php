<?php

declare(strict_types=1);

namespace Alexandr\Store\Api\Data;

interface StoreInterface
{
    /**
     *
     */
    const ID = 'entity_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const IMAGE = 'image';
    const ADDRESS = 'address';
    const SCHEDULE = 'schedule';
    const LONGITUDE = 'longitude';
    const LATITUDE = 'latitude';
    const STORE_URL_KEY = 'store_url_key';

    /**
     * @return int|string
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     * @return StoreInterface
     */
    public function setName(?string $name): StoreInterface;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     * @return StoreInterface
     */
    public function setDescription(?string $description): StoreInterface;

    /**
     * @return string|array|null
     */
    public function getImage(): ?string;

    /**
     * @param string | array $image
     * @return StoreInterface
     */
    public function setImage($image): StoreInterface;

    /**
     * @return string
     */
    public function getAddress(): string;

    /**
     * @param string|null $address
     * @return StoreInterface
     */
    public function setAddress(?string $address): StoreInterface;

    /**
     * @return string|null
     */
    public function getSchedule(): ?string;

    /**
     * @param $schedule
     * @return StoreInterface
     */
    public function setSchedule($schedule): StoreInterface;

    /**
     * @return string|null
     */
    public function getLongitude(): ?string;

    /**
     * @param string|null $longitude
     * @return StoreInterface
     */
    public function setLongitude(?string $longitude): StoreInterface;

    /**
     * @return string|null
     */
    public function getLatitude(): ?string;

    /**
     * @param string|null $latitude
     * @return StoreInterface
     */
    public function setLatitude(?string $latitude): StoreInterface;

    /**
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * @param string $store_url_key
     * @return StoreInterface
     */
    public function setUrl(string $store_url_key): StoreInterface;
}
