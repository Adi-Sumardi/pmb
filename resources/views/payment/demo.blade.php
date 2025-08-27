{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/payment/demo.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🧪 {{ __('Demo Payment Gateway') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Demo Mode Alert -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                    Demo Mode Active
                                </h3>
                                <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                                    This is a simulation of Xendit payment gateway for testing purposes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Payment Details
                        </h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Student Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->pendaftar->nama_murid }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Registration No</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $payment->pendaftar->no_pendaftaran }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Level</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $jenjangName }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->pendaftar->unit }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                <dd class="mt-1 text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    @if($payment->status === 'PENDING')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            {{ $payment->status }}
                                        </span>
                                    @elseif($payment->status === 'PAID')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ $payment->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            {{ $payment->status }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">External ID</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $payment->external_id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->created_at->format('d/m/Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($payment->status === 'PENDING')
                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <form action="{{ route('payment.demo.pay', $payment->external_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="success">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simulate Successful Payment
                                </button>
                            </form>

                            <form action="{{ route('payment.demo.pay', $payment->external_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="failed">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Simulate Failed Payment
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Payment Already Processed -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                        Payment Already Processed
                                    </h3>
                                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                        This payment has already been {{ strtolower($payment->status) }}.
                                        @if($payment->paid_at)
                                            Processed at: {{ $payment->paid_at->format('d/m/Y H:i:s') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Back Button -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('payment.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Payment Dashboard
                        </a>
                    </div>

                    <!-- Info Note -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            In production, this would redirect to actual Xendit payment page
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
