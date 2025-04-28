<?php

namespace App\Controllers;

class DatabaseFix extends BaseController
{
    public function index()
    {
        echo '<h1>Database Structure Fix</h1>';
        
        try {
            $db = \Config\Database::connect();
            
            // Check if the letter_types table has the status column
            $hasStatus = $db->fieldExists('status', 'letter_types');
            if (!$hasStatus) {
                echo '<p>Adding status column to letter_types table...</p>';
                $db->query("ALTER TABLE letter_types ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER description");
                echo '<p class="text-success">Status column added successfully!</p>';
            } else {
                echo '<p>Status column already exists in letter_types table.</p>';
            }
            
            // Check if the letter_types table has the deleted_at column
            $hasDeletedAt = $db->fieldExists('deleted_at', 'letter_types');
            if (!$hasDeletedAt) {
                echo '<p>Adding deleted_at column to letter_types table...</p>';
                $db->query("ALTER TABLE letter_types ADD COLUMN deleted_at DATETIME NULL AFTER updated_at");
                echo '<p class="text-success">deleted_at column added successfully!</p>';
            } else {
                echo '<p>deleted_at column already exists in letter_types table.</p>';
            }
            
            // Set status to active for all existing letter types
            if ($hasStatus) {
                $db->query("UPDATE letter_types SET status = 'active' WHERE status IS NULL");
                echo '<p>Updated all NULL status values to "active"</p>';
            }
            
            // Check if the letter_requests table has the status column
            $hasStatusRequests = $db->fieldExists('status', 'letter_requests');
            if (!$hasStatusRequests) {
                echo '<p>Adding status column to letter_requests table...</p>';
                $db->query("ALTER TABLE letter_requests ADD COLUMN status ENUM('pending', 'processing', 'approved', 'rejected', 'completed') DEFAULT 'pending'");
                echo '<p class="text-success">Status column added to letter_requests table!</p>';
            } else {
                echo '<p>Status column already exists in letter_requests table.</p>';
            }
            
            echo '<div style="margin-top: 20px; padding: 10px; background-color: #dff0d8; border: 1px solid #d6e9c6; border-radius: 4px;">';
            echo '<h3 style="color: #3c763d;">Database structure updated successfully!</h3>';
            echo '<p>The missing columns have been added to your database tables.</p>';
            echo '<p><a href="' . base_url('letter-requests/my-requests') . '" style="color: #337ab7;">Go back to Letter Requests</a></p>';
            echo '</div>';
            
        } catch (\Exception $e) {
            echo '<div style="margin-top: 20px; padding: 10px; background-color: #f2dede; border: 1px solid #ebccd1; border-radius: 4px;">';
            echo '<h3 style="color: #a94442;">Error occurred!</h3>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '<p>Please run the migration manually or contact the system administrator.</p>';
            echo '</div>';
        }
        
        return '';
    }
} 