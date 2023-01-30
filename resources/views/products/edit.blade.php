<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center text-white">
        <form method="POST" action="{{route('products.update', $product)}}">
            @csrf

            {{-- Title --}}
            <div>
                <label for="title">{{__('Title')}}</label>
                <input id="title" class="block mt-1 w-full text-black" value="{{$product->title}}" type="text" name="title" />
            </div>

            {{-- Price --}}
            <div>
                <label for="price">{{__('Price')}}</label>
                <input id="price" class="block mt-1 w-full text-black"  value="{{$product->price}}" type="number" name="price" />
            </div>

            <div>
                <button>{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</x-guest-layout>
