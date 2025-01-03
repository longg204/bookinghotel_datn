@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All Rooms</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index.html">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">All Rooms</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                       tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.room.add') }}"><i
                            class="icon-plus"></i>Add new</a>
                </div>
                <div class="table-responsive">
                    @if(Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Sale Price</th>
                                <th>Category</th>
                                <th>Featured</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $key => $room)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset("/uploads/room") }}/{{ $room->image }}" alt="{{ $room->name }}" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="#" class="body-title-2">{{ $room->name }}</a>
                                            <div class="text-tiny mt-3">{{ $room->price }}</div>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden">{{ $room->regular_price }}</td>
                                    <td style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden">{{ $room->sale_price }}</td>
                                    <td>{{ $room->category_id }}</td>
                                    <td>{{ $room->featured }}</td>
                                    <td>{{ $room->stock_status }}</td>
                                    <td>{{ $room->quantity }}</td>
                                    <td style="width: max-content">
                                        <div class="list-icon-function" style="width: max-content">
                                            <a href="#" target="_blank">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </a>
                                            <a href="{{ route("admin.room.edit", $room->id) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route("admin.room.delete", $room->id) }}" method="POST">
                                                @csrf
                                                @method("DELETE")
                                                <div class="item text-danger delete">
                                                    <i class="icon-trash-2"></i>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $rooms->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault()
                var form = $(this).closest('form');
                swal({
                    title: 'Are you sure?',
                    text: 'You want delete the record?',
                    type: 'Warning',
                    buttons: ["No", "Yes"],
                    confirmButtonColor: '#1affed',
                }).then(function(result) {
                    if(result) {
                        form.submit();
                    }
                })
            })
        })
    </script>
@endpush
