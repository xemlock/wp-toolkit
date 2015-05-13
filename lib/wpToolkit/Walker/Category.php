<?php

class wpToolkit_Walker_Category extends Walker_Category
{
    protected $_category;

    /**
     * {@inheritDoc}
     *
     * Additionally an 'item_format' option is supported that allows more
     * flexible generation of category items.
     */
    public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0)
    {
        if (isset($args['item_format'])) {
            $show_count = false;

            if (isset($args['show_count']) && $args['show_count']) {
                unset($args['show_count']);
                $show_count = true;
            }

            parent::start_el($out, $category, $depth, $args, $id);

            // remove any trailing <br /> tag, as well as closing </a> tag
            $out = preg_replace('#</?(br|a)\s*/?>#i', '', $out);

            // determine position of the right bracket which closes a tag
            $pos = strpos($out, '>', stripos($out, '<a '));

            $prefix = substr($out, 0, $pos + 1); // contains opening tags for LI and A
            $name = trim(substr($out, $pos + 1));

            $this->_category = $category;

            $item = $args['item_format'];
            $item = preg_replace('#%\{\s*name\s*\}#', $name, $item); // use filtered category name
            $item = preg_replace_callback('#%\{\s*([_a-z0-9]+)\s*\}#i', array($this, 'format_helper'), $item);

            $out = $prefix . $item . '</a>';

            if ($show_count) {
                $out .= ' (' . number_format_i18n($category->count) . ')';
            }

            $out .= ('list' === $args['style'] ? '' : '<br />') . "\n";

            $output .= $out;
            return;
        }

        parent::start_el($output, $category, $depth, $args, $id);
    }

    protected function format_helper(array $match)
    {
        $key = $match[1];
        if (isset($this->_category->{$key})) {
            $value = $this->_category->{$key};

            switch ($key) {
                case 'count':
                    $value = number_format_i18n($value);
                    break;

                default:
                    $value = esc_html($value);
                    break;
            }

            return $value;
        }
        return $match[0];
    }
}
