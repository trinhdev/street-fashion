<div class="row">
    <div class="col-md-12">
        <form id="formCategories" novalidate="novalidate" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="category_child_name">Tên danh mục</label>
                        <input type="text"  id="slug" name="category_child_name" onkeyup="ChangeToSlug();" class="form-control" placeholder="Tên danh mục">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" id="convert_slug" name="slug"  class="form-control" placeholder="Slug">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="categories_parent_id">Danh mục cha</label>
                        <select name="categories_parent_id" id="categories_parent_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Vui lòng chọn danh mục cha</option>
                            @foreach ($data['list_category_parent'] as $category_parent)
                                <option value="{{ $category_parent->id }}">{{ $category_parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                   
                </div>
            </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="button" onclick="pushCategory()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
