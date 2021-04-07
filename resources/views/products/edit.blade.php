<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Productos') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded relative" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{route('products.update', $product)}}">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-label for="name" :value="__('Nombre')" />

                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $product->name" required autofocus />
                        </div>

                        <div class="mt-4 grid grid-flow-col grid-rows-1 grid-cols-2">
                            <div>
                                <x-label for="price" :value="__('Precio')" />

                                <span>$ </span><x-input id="price" class="mt-1 w-24" type="number" name="price" :value="old('price') ?? $product->price" required />
                            </div>
                            <div class="my-auto">
                                <x-button class="ml-3 float-right">
                                    {{ __('Actualizar producto') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
