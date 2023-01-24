<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center text-white">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Price EUR</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            {{ $product->title }}
                        </td>
                        <td>
                            {{ $product->price }}
                        </td>
                        <td>
                            {{ $product->price_eur }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            {{__("Mo Products Found")}}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-guest-layout>
