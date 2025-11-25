@extends('layouts/contentNavbarLayout')

@section('title', 'Manage Types & Items')

@section('content')

    <h4 class="py-3 mb-4">{{ $category->name }} → Manage Types & Items</h4>

    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mb-3">← Back</a>

    <form action="{{ route('admin.categories.types.save', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div id="types-wrapper">

            {{-- EXISTING TYPES + ITEMS --}}
            @foreach ($subItems as $type => $items)
                <div class="type-block card p-3 mb-3" data-type-id="{{ $items->first()->id }}">

                    <div class="d-flex justify-content-between">
                        <h5>Type</h5>
                        <button type="button" class="btn btn-danger btn-sm delete-type-btn"
                            data-type-name="{{ $type }}">
                            X
                        </button>
                    </div>

                    {{-- Hidden field to track original type name --}}
                    <input type="hidden" name="types[{{ $loop->index }}][existing_type]" value="{{ $type }}">
                    <input type="hidden" name="types[{{ $loop->index }}][type_id]" value="{{ $items->first()->id }}">

                    <div class="mb-3">
                        <label class="form-label">Type Name</label>
                        <input type="text" name="types[{{ $loop->index }}][name]" value="{{ $type }}"
                            class="form-control" required>
                    </div>


                    <div class="items-wrapper">
                        @foreach ($items as $item)
                            <div class="item-block border p-2 mb-2 rounded" data-item-id="{{ $item->id }}">

                                <div class="d-flex justify-content-between">
                                    <label>Item</label>
                                    <button type="button" class="btn btn-danger btn-sm delete-item-btn"
                                        data-item-id="{{ $item->id }}">
                                        X
                                    </button>
                                </div>

                                {{-- Hidden field to track existing item --}}
                                <input type="hidden"
                                    name="types[{{ $loop->parent->index }}][items][{{ $loop->index }}][existing_item_id]"
                                    value="{{ $item->id }}">

                                <input type="text"
                                    name="types[{{ $loop->parent->index }}][items][{{ $loop->index }}][name]"
                                    value="{{ $item->item }}" class="form-control mb-2" required>

                                @if ($item->item_image)
                                    <img src="{{ asset('storage/' . $item->item_image) }}" width="60" height="60"
                                        class="rounded mb-2">
                                @endif

                                <input type="file"
                                    name="types[{{ $loop->parent->index }}][items][{{ $loop->index }}][image]"
                                    class="form-control" accept="image/*">
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-primary btn-sm add-item mt-2">+ Add Item</button>
                </div>
            @endforeach

        </div>

        <button type="button" class="btn btn-info mt-3" id="add-type">+ Add New Type</button>
        <button type="submit" class="btn btn-success mt-3 float-end">Save All</button>

    </form>

@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let typeIndex = {{ count($subItems) }};

        // ADD NEW TYPE
        document.getElementById('add-type').addEventListener('click', function() {
            const wrapper = document.getElementById('types-wrapper');
            const html = `
            <div class="type-block card p-3 mb-3">
                <div class="d-flex justify-content-between">
                    <h5>Type (New)</h5>
                    <button type="button" class="btn btn-danger btn-sm remove-type">X</button>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type Name</label>
                    <input type="text" name="types[${typeIndex}][name]" class="form-control" required>
                </div>
                <div class="items-wrapper"></div>
                <button type="button" class="btn btn-primary btn-sm add-item mt-2">+ Add Item</button>
            </div>
        `;
            wrapper.insertAdjacentHTML('beforeend', html);
            typeIndex++;
        });

        // ADD ITEM
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-item')) {
                const typeBlock = e.target.closest('.type-block');
                const wrapper = typeBlock.querySelector('.items-wrapper');
                let itemIndex = wrapper.children.length;
                const typeNum = [...document.querySelectorAll('.type-block')].indexOf(typeBlock);

                const html = `
                <div class="item-block border p-2 mb-2 rounded">
                    <div class="d-flex justify-content-between">
                        <label>Item (New)</label>
                        <button type="button" class="btn btn-danger btn-sm remove-item">X</button>
                    </div>
                    <input type="text" name="types[${typeNum}][items][${itemIndex}][name]" class="form-control mb-2" required>
                    <input type="file" name="types[${typeNum}][items][${itemIndex}][image]" class="form-control" accept="image/*">
                </div>
            `;
                wrapper.insertAdjacentHTML('beforeend', html);
            }
        });

        // DELETE EXISTING TYPE (DB)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-type-btn')) {
                let typeName = e.target.dataset.typeName;

                Swal.fire({
                    title: "Delete entire Type?",
                    text: "This will delete the type & all its items!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('admin.subcategory.type.delete', $category->id) }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    type: typeName
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    e.target.closest('.type-block').remove();
                                    Swal.fire('Deleted!', 'Type has been deleted.', 'success');
                                }
                            })
                            .catch(err => {
                                Swal.fire('Error!', 'Failed to delete type.', 'error');
                            });
                    }
                });
            }
        });

        // DELETE EXISTING ITEM (DB)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-item-btn')) {
                let itemId = e.target.dataset.itemId;

                Swal.fire({
                    title: "Delete item?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ url('/admin/subcategory/item') }}/" + itemId, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    e.target.closest('.item-block').remove();
                                    Swal.fire('Deleted!', 'Item has been deleted.', 'success');
                                }
                            })
                            .catch(err => {
                                Swal.fire('Error!', 'Failed to delete item.', 'error');
                            });
                    }
                });
            }
        });

        // DELETE UNSAVED TYPE
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-type')) {
                e.target.closest('.type-block').remove();
            }
        });

        // DELETE UNSAVED ITEM
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-block').remove();
            }
        });
    </script>
@endsection
