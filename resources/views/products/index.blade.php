@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" value="{{ old('title') }}">
                </div>
                <div class="col-md-2">
                    <select name="variant" class="form-control">
                        <option value="" selected disabled>Search Variant</option>
                        @php
                            $curVar = '';
                        @endphp
                        @foreach ($variants as $item)
                            @php
                                $prevVar = $item->variantParent->title;
                            @endphp
                            @if ($prevVar != $curVar)
                                <option class="" value="" disabled>{{ $prevVar }}</option>
                            @php
                                $curVar = $prevVar;
                            @endphp
                            @endif
                                <option value="{{ $item->variant }}"> - {{ $item->variant }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control" value="{{ old('price_from') }}">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control" value="{{ old('price_to') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control" value="{{ old('date') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th width="10%">Title</th>
                        <th width="35%">Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach ($products as $product)
                    <tr>
                        <td>
                            {{ ($products->perPage()*($products->currentPage()-1))+$loop->iteration }}
                        </td>
                        <td>{{ $product->title }} <br> Created at : {{ now()->parse($product->created_at)->format('d-M-Y') }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                @foreach ($product->variantPrices as $variant)

                                <dt class="col-sm-3 pb-0">
                                    {{ $variant->productVariantOne->variant ?? '' }}/ {{ $variant->productVariantTwo->variant ?? '' }}/ {{ $variant->productVariantThree->variant ?? '' }}
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($variant->price,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format($variant->stock,2) }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', [$product->id]) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- <tr>
                        <td>1</td>
                        <td>T-Shirt <br> Created at : 25-Aug-2020</td>
                        <td>Quality product in low cost</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                <dt class="col-sm-3 pb-0">
                                    SM/ Red/ V-Nick
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format(200,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format(50,2) }}</dd>
                                    </dl>
                                </dd>
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr> --}}

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} out of {{ $totalProductCount }}</p>
                </div>
                <div class="col-md-2">
                    {{ $products->render() }}
                </div>
            </div>
        </div>
    </div>

@endsection
