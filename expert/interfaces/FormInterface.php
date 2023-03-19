<?php

namespace expert\interfaces;

interface FormInterface
{
    public function run();

    public static function getCurrentModelFormId();

    public function getFile();
}