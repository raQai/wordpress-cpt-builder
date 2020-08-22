<?php

namespace BIWS\EventManager\taxonomy\fields;

defined('ABSPATH') or die('Nope!');

abstract class FieldType
{
    const NUMBER = 0;
    const TEXT = 1;
    const COLOR = 2;
    const IMAGE = 3;
}
