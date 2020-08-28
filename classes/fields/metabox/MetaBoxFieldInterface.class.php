<?php

namespace BIWS\EventManager\fields\metabox;

use BIWS\EventManager\fields\FieldInterface;

defined('ABSPATH') or die('Nope!');

interface MetaBoxFieldInterface extends FieldInterface
{
    function renderForScript();
}
