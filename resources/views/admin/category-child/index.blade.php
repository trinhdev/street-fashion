@extends('admin.layoutv2.layout.app')

@section('content')
    <div id="wrapper"> 
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <a onclick="addCategoryChild(event)" class="btn btn-primary mright5 test pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            Thêm mới</a>
                        <a href="#" onclick="alert('Liên hệ tuanhhcps30852@fpt.edu.vn nếu xảy ra lỗi không mong muốn!')"
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
                                {{ $dataTable->table(['id' => 'category_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.template.modal', ['id' => 'showDetail_Modal', 'title'=>'Thêm danh mục', 'form'=>'admin.category-child.list'])
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
function ChangeToSlug()
        {
            var slug;
         
            //Lấy text từ thẻ input title 
            slug = document.getElementById("slug").value;
            slug = slug.toLowerCase();
            //Đổi ký tự có dấu thành không dấu
                slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
                slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
                slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
                slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
                slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
                slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
                slug = slug.replace(/đ/gi, 'd');
                //Xóa các ký tự đặt biệt
                slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
                //Đổi khoảng trắng thành ký tự gạch ngang
                slug = slug.replace(/ /gi, "-");
                //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
                //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
                slug = slug.replace(/\-\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-/gi, '-');
                slug = slug.replace(/\-\-/gi, '-');
                //Xóa các ký tự gạch ngang ở đầu và cuối
                slug = '@' + slug + '@';
                slug = slug.replace(/\@\-|\-\@|\@/gi, '');
                //In slug ra textbox có id “slug”
            document.getElementById('convert_slug').value = slug;
        }

        function deleteCategoryChild(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/admin/category-child/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#categories_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function addCategoryChild(e) {
                e.preventDefault();
                $('#showDetail_Modal').modal('toggle');
                document.getElementById('formCategoryChild').reset(); // Reset tất cả các trường input
               
                window.urlMethod = '/admin/category-child/store'; // Thiết lập phương thức URL cho thêm mới
                window.type = 'POST'; // Thiết lập phương thức HTTP
            }


        function detailCategoryChild(_this) {
    let dataPost = {};
    dataPost.id = $(_this).data('id');
    $.post('/admin/category-child/show', dataPost).done(function (response) {
        console.log(response.data);
        for (let [key, value] of Object.entries(response.data)) {
            let k = $('#' + key);
            k.val(value);
            k.trigger('change');
        }

        $('#showDetail_Modal').modal('toggle');
        window.urlMethod = '/admin/category-child/update/' + $(_this).data('id');
        window.type = 'PUT';
    });
}


        function pushCategoryChild() {
            $(this).attr('disabled', 'disabled');
            let data = $('#formCategoryChild').serialize();
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
