<?php

/**
 * Adapter for handling Gravity Forms events
 */
class GravityViewAdapter {

    private TaskStatus $taskStatus;


    public function __construct( TaskStatus $taskStatus){
        $this->taskStatus        = $taskStatus;

        $this->register_hooks();
    }

    /**
     * Register gravity forms hooks to handle custom logic
     *
     * @return void
     */
    public function register_hooks() {
        add_action('gravityview/edit_entry/after_update', [$this->taskStatus, 'handle_stage_change',], 10, 3);
    }
}