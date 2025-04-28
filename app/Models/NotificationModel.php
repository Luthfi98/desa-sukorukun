<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'title', 'message', 'link', 'is_read', 'read_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get notifications by user ID
     */
    public function getUserNotifications($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get the count of unread notifications by user ID
     */
    public function countUnread($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }
    
    /**
     * Alias for countUnread (for backward compatibility)
     */
    public function getUnreadCount($userId)
    {
        return $this->countUnread($userId);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        return $this->update($id, [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        $notifications = $this->where('user_id', $userId)
                              ->where('is_read', 0)
                              ->findAll();
                              
        foreach ($notifications as $notification) {
            $this->update($notification['id'], [
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        return true;
    }

    /**
     * Create a notification
     */
    public function createNotification(int $userId, string $title, string $message, string $link = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'is_read' => false
        ]);
    }

    /**
     * Create a notification for letter request status changes
     */
    public function createForLetterRequest(int $userId, string $status, array $letterRequest)
    {
        $title = '';
        $message = '';
        
        switch ($status) {
            case 'processing':
                $title = 'Permohonan Surat Diproses';
                $message = 'Permohonan surat ' . $letterRequest['letter_type_name'] . ' Anda sedang diproses.';
                break;
            case 'approved':
                $title = 'Permohonan Surat Disetujui';
                $message = 'Permohonan surat ' . $letterRequest['letter_type_name'] . ' Anda telah disetujui.';
                break;
            case 'rejected':
                $title = 'Permohonan Surat Ditolak';
                $message = 'Permohonan surat ' . $letterRequest['letter_type_name'] . ' Anda ditolak. Alasan: ' . $letterRequest['rejection_reason'];
                break;
            case 'completed':
                $title = 'Surat Selesai';
                $message = 'Surat ' . $letterRequest['letter_type_name'] . ' Anda telah selesai dan siap diambil/diunduh.';
                break;
            default:
                $title = 'Update Status Permohonan Surat';
                $message = 'Status permohonan surat ' . $letterRequest['letter_type_name'] . ' Anda diperbarui menjadi: ' . $status;
        }
        
        return $this->insert([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => 'letter_requests/view/' . $letterRequest['id'],
            'is_read' => false,
        ]);
    }
} 