<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NotificationService
{
    private $matriculaWebhookUrl;
    private $professorWebhookUrl;

    public function __construct()
    {
        // URL atualizada do webhook do n8n
        $this->matriculaWebhookUrl = 'http://localhost:5678/webhook-test/webhook/matricula';
        $this->professorWebhookUrl = 'http://localhost:5678/webhook/professor';
    }

    public function notifyNewStudent($student)
    {
        try {
            Http::post($this->matriculaWebhookUrl, [
                'name' => $student->name,
                'contact' => $student->contact,
                'email' => $student->email,
                'type' => 'student_registration'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificação de matrícula: ' . $e->getMessage());
        }
    }

    public function notifyNewProfessor($professor)
    {
        try {
            Http::post($this->professorWebhookUrl, [
                'name' => $professor->name,
                'contact' => $professor->contact,
                'email' => $professor->email,
                'type' => 'professor_registration'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificação de professor: ' . $e->getMessage());
        }
    }
} 
