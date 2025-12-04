<?php

namespace App\Mail;

use App\Models\Grade;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GradeNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Grade $grade;

    public function __construct(Grade $grade)
    {
        $this->grade = $grade;
    }

    public function build()
    {
        return $this->subject('Notificación de calificación')
            ->markdown('emails.grades.notification', [
                'grade'  => $this->grade,
                'alumno' => $this->grade->alumno,
                'materia'=> $this->grade->materia,
            ]);
    }
}
