<?php

namespace Learn\MyApi\Api;

interface LearnMyApiInterface {

    /**
     * MyApi Api
     *
     * @param string $name
     * @return string
     */
    public function hello($name);

    /**
     * @return \Learn\MyApi\Api\Data\UserInterface
     */
    public function getUser();
}
