<?php

namespace App\UI\Http\Controllers;

use App\Application\ProcessAndDistributeContent;
use App\Domain\Repositories\MessageRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;

use App\UI\Http\Requests\StoreMessageRequest;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function __construct(
        private ProcessAndDistributeContent $processUseCase,
        private MessageRepository $messageRepository
    ) {}

    public function index()
    {
        $messages = $this->messageRepository->getAll();
        return Inertia::render('Dashboard', [
            'messages' => $messages
        ]);
    }

    public function create()
    {
        return Inertia::render('CreateMessage');
    }

    public function store(StoreMessageRequest $request)
    {
        try {
            $this->processUseCase->execute(
                $request->validated('title'),
                $request->validated('content'),
                $request->validated('channels')
            );

            return redirect()->route('dashboard')->with('success', 'Contenido procesado y distribuido correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

