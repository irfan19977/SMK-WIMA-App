<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationBell extends Component
{
    public $notifications;
    public $unreadCount;
    public $showDropdown = false;

    protected $listeners = ['notificationReceived' => '$refresh'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function loadNotifications()
    {
        $user = auth()->user();
        if ($user) {
            $this->notifications = $user->notifications()->latest()->take(5)->get();
            $this->unreadCount = $user->unreadNotifications()->count();
        } else {
            $this->notifications = collect();
            $this->unreadCount = 0;
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = DatabaseNotification::find($notificationId);
        if ($notification && $notification->notifiable_id == auth()->id()) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.admin.notification-bell');
    }
}
