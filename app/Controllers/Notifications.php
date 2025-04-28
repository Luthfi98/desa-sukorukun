<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected $notificationModel;
    
    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }
    
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $userId = session()->get('user_id');
        
        $data = [
            'title' => 'Notifikasi',
            'notifications' => $this->notificationModel->getUserNotifications($userId)
        ];
        
        return view('notifications/index', $data);
    }
    
    public function markAsRead($id)
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $userId = session()->get('user_id');
        $notification = $this->notificationModel->find($id);
        
        if (!$notification || $notification['user_id'] != $userId) {
            return redirect()->to(base_url('notifications'))->with('error', 'Notifikasi tidak ditemukan');
        }
        
        $this->notificationModel->markAsRead($id);
        
        // If notification has link, redirect to that link
        if (!empty($notification['link'])) {
            return redirect()->to(base_url($notification['link']));
        }
        
        return redirect()->to(base_url('notifications'))->with('message', 'Notifikasi telah ditandai sebagai telah dibaca');
    }
    
    public function markAllAsRead()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $userId = session()->get('user_id');
        
        $this->notificationModel->markAllAsRead($userId);
        
        return redirect()->to(base_url('notifications'))->with('message', 'Semua notifikasi telah ditandai sebagai telah dibaca');
    }
} 