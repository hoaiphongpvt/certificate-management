<?php
interface TFNumbersOpsInterface {
    // section data array
    public function get_section( $prefix);

    // options array
    public function get_options();

    // initialization call
    public function init( $prefix);
}
