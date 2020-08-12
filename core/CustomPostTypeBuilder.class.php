<?php

namespace BIWS\CPT\Builder;

use BIWS\CPT\CustomPostType;

defined('ABSPATH') or die('Nope!');

class CustomPostTypeBuilder
{
    private $slug;
    private $args;

    public static function create()
    {
        return new CustomPostTypeBuilder();
    }

    public function slug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function args($args)
    {
        $this->args = $args;
        return $this;
    }

    public function buildAndInit()
    {
        $cpt = new CustomPostType($this->slug, $this->args);
        $cpt->init();
        return $cpt;
    }
}
