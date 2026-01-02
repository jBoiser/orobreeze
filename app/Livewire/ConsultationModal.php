<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultationRequest;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;

class ConsultationModal extends Component
{
    use UsesSpamProtection;

    public $isOpen = false;
    public $email;
    public $message;
    public $submitted = false;

    public ?HoneypotData $honeypotData = null;

    public function mount()
    {
        // Initialize it here
        $this->honeypotData = new HoneypotData();
    }

    // --- ADD THIS METHOD ---
    public function toggleModal()
    {
        $this->isOpen = !$this->isOpen;

        // Reset the success state if we are closing the modal
        if (!$this->isOpen) {
            $this->submitted = false;
        }
    }

    // -----------------------

    public function sendRequest()
    {
        $this->protectAgainstSpam();
        $this->validate(['email' => 'required|email', 'message' => 'required']);

        Mail::to('orobreeze@gmail.com')->send(new ConsultationRequest($this->email, $this->message));

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.consultation-modal');
    }
}
