import React, { useState } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';

export default function CreateMessage() {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        content: '',
        channels: [] as string[],
    });

    const handleChannelChange = (channel: string) => {
        if (data.channels.includes(channel)) {
            setData('channels', data.channels.filter(c => c !== channel));
        } else {
            setData('channels', [...data.channels, channel]);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/messages');
    };

    return (
        <div className="min-h-screen bg-gray-100 p-8">
            <Head title="Nuevo Envío" />
            <div className="max-w-2xl mx-auto">
                <div className="mb-8 flex items-center gap-4">
                    <Link href="/dashboard" className="text-blue-600 hover:underline">← Volver</Link>
                    <h1 className="text-3xl font-bold text-gray-800">Enviar Nuevo Contenido</h1>
                </div>

                <div className="bg-white rounded-xl shadow p-8">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Título</label>
                            <input
                                type="text"
                                value={data.title}
                                onChange={e => setData('title', e.target.value)}
                                className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Ej: Anuncio de actualización"
                            />
                            {errors.title && <p className="text-red-500 text-xs mt-1">{errors.title}</p>}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                            <textarea
                                value={data.content}
                                onChange={e => setData('content', e.target.value)}
                                rows={6}
                                className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Escribe el contenido completo aquí..."
                            ></textarea>
                            {errors.content && <p className="text-red-500 text-xs mt-1">{errors.content}</p>}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">Canales de Distribución</label>
                            <div className="flex gap-6">
                                {['email', 'slack', 'sms'].map(channel => (
                                    <label key={channel} className="flex items-center gap-2 cursor-pointer capitalize">
                                        <input
                                            type="checkbox"
                                            checked={data.channels.includes(channel)}
                                            onChange={() => handleChannelChange(channel)}
                                            className="rounded text-blue-600 focus:ring-blue-500 w-5 h-5"
                                        />
                                        <span className="text-gray-700">{channel === 'sms' ? 'SMS Legacy (SOAP)' : channel}</span>
                                    </label>
                                ))}
                            </div>
                            {errors.channels && <p className="text-red-500 text-xs mt-1">{errors.channels}</p>}
                        </div>

                        {errors.error && (
                            <div className="bg-red-50 border-l-4 border-red-400 p-4">
                                <p className="text-sm text-red-700">{errors.error}</p>
                            </div>
                        )}

                        <div className="pt-4">
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                            >
                                {processing ? 'Procesando con IA...' : 'Procesar y Distribuir'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
