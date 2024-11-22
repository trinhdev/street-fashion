@extends('admin.layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <a onclick="addCategoryParent(event)" class="btn btn-primary mright5 test pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            Thêm mới</a>
                        <a href="#" onclick="alert('Liên hệ tuan16@fpt.com nếu xảy ra lỗi không mong muốn!')"
                           class="btn btn-default pull-left display-block mright5">
                            <i class="fa-regular fa-user tw-mr-1"></i>Liên hệ
                        </a>
                        <div class="visible-xs">
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            <div class="panel-table-full">
                                {{ $dataTable->table(['id' => 'categories_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.template.modal', ['id' => 'showDetail_Modal', 'title'=>'Thêm danh mục', 'form'=>'admin.category.create'])
@endsection
@push('script')
    {{ $dataTable->scripts() }}
    <script>

        function responseImageStatic(res, input) {
            console.log(res);
            if (res.statusCode === 0 && res.data !== null) {
                const [file] = input.files;
                const input_name = 'img_' + input.name;
                console.log(input_name);
                document.getElementById(input_name).src = URL.createObjectURL(file);
                console.table(input_name + '_name', res.data.uploadedImageFileName)
                document.getElementById(input_name + '_name').value = res.data.uploadedImageFileName;
                console.log(res.data.uploadedImageFileName);
            } else {
                alert_float('danger',res.message);
            }
        }

        function handleUploadImage(input) {
            const [file] = input.files;
            if (file.size > 700000) { // handle file
                resetData(input, null);
                alert_float('danger','File is too big! Allowed memory size of 0.7MB');
                return false;
            };
            uploadFileStatic(file, input, responseImageStatic);
        }

        function dialogConfirmWithAjaxCategoryParent(sureCallbackFunction, data, text = "Xin hay kiểm tra lại") {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Chắc chắn!',
                reverseButtons: true

            }).then((result) => {
                if (result.isConfirmed) {
                    sureCallbackFunction(data);
                }else {
                    let table = $('#categories_manage').DataTable();
                    table.ajax.reload(null, false);
                }
            });
        }
     
        function changeStatusCategoriesParent(data){
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('admin/category/change-status', dataPost).done(function(response) {
                alert_float('success', response.message);
                $('#categories_manage').DataTable().ajax.reload(null,false);
            });
        }
    
        function deleteCategoryParent(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/admin/category-parent/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#categories_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function addCategoryParent(e) {
                e.preventDefault();
                $('#showDetail_Modal').modal('toggle');
                document.getElementById('formCategoriesParent').reset(); // Reset tất cả các trường input
               
                window.urlMethod = '/admin/category-parent/store'; // Thiết lập phương thức URL cho thêm mới
                window.type = 'POST'; // Thiết lập phương thức HTTP
            }


        function detailCategoryParent(_this) {
    let dataPost = {};
    dataPost.id = $(_this).data('id');
    $.post('/admin/category-parent/show', dataPost).done(function (response) {
        console.log(response.data);
        for (let [key, value] of Object.entries(response.data)) {
            let k = $('#' + key);
            k.val(value);
            k.trigger('change');
        }

        $('#showDetail_Modal').modal('toggle');
        window.urlMethod = '/admin/category-parent/update/' + $(_this).data('id');
        window.type = 'PUT';
    });
}


        function pushCategoryParent() {
            $(this).attr('disabled', 'disabled');
            let data = $('#formCategoriesParent').serialize();
            $.ajax({
                url: urlMethod,
                type: window.type,
                dataType: 'json',
                data: data,
                cache: false,
                success: (data) => {
                    if (data.success) {
                        $('#showDetail_Modal').modal('toggle');
                        alert_float('success', data.message);
                        let table = $('#categories_manage').DataTable();
                        table.ajax.reload(null, false);
                        $('#submit').prop('disabled', false);
                    } else {
                        alert_float('danger', data.message);
                        $('#submit').prop('disabled', false);
                    }
                }, error: function (xhr) {
                    let errorString = xhr.responseJSON.message ?? '';
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorString = value;
                        return false;
                    });
                    alert_float('danger', errorString);
                    $('#submit').prop('disabled', false);
                }
            });
        }
    </script>
@endpush
