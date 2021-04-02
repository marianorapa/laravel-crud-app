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
                    <form method="POST" action="{{route('products.store')}}">
                        @csrf
                        <div>
                            <x-label for="name" :value="__('Nombre')" />

                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <div class="mt-4 grid grid-flow-col grid-rows-1 grid-cols-2">
                            <div>
                                <x-label for="price" :value="__('Precio')" />

                                <span>$ </span><x-input id="price" class="mt-1 w-20" type="number" name="price" :value="old('price')" required />
                            </div>
                            <div class="my-auto">
                                <x-button class="ml-3 float-right">
                                    {{ __('Guardar producto') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
