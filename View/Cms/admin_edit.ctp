<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css">
<div class="content">
      <div class="">
	 
        <div class="page-header-title">
		 <?php if(!isset($this->request->data['Office']['id'])):?>
          <h4 class="page-title">Add Office</h4>
		  <?php else: ?>
		  <h4 class="page-title">Edit Office</h4>
		  <?php endif; ?>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				
                  <div class="row">
				    <?php echo $this->Form->create('Cms', array('novalidate' => true,'url'=>array('controller'=>'cms','action'=>'admin_edit')));?>
                     <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
                     
					<div class="col-sm-12 col-xs-12">
						<div class="m-t-20">
					  
							<div class="form-group">
								<label>Page Name</label>
								<?php echo $this->Form->input('page_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name Of Cms",'required'=>true, 'readonly'=>'readonly')); ?>
							</div>
							
							<div class="form-group">
								<label>Page Title</label>
								<?php echo $this->Form->input('title',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Title",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Content</label>
								<?php echo $this->Form->input('content',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content",'required'=>true)); ?>
							</div>
							<div class="form-group m-b-0">
								<div>
									<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
								</div>
							</div>
						</div>
					<?php echo $this->Form->end();?>
					</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	
 <script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.2/"
                }
            }
        </script>
        <script type="module">
            import {
                ClassicEditor,
                Essentials, Paragraph, Bold, Italic, Underline, Strikethrough, Subscript, Superscript, Code, Link,
            List, Indent, BlockQuote, MediaEmbed, ImageUpload, Highlight, FontSize, FontFamily,
            FontColor, FontBackgroundColor, Alignment, RemoveFormat, HorizontalLine, PageBreak, SpecialCharacters,
            FindAndReplace, SourceEditing
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#CmsContent' ), {
                    plugins: [ Essentials, Paragraph, Bold, Italic, Underline, Strikethrough, Subscript, Superscript, Code, Link,
            List, Indent, BlockQuote, MediaEmbed, ImageUpload, Highlight, FontSize, FontFamily,
            FontColor, FontBackgroundColor, Alignment, RemoveFormat, HorizontalLine, PageBreak, SpecialCharacters,
            FindAndReplace, SourceEditing ],
                    toolbar: [
            'undo', 'redo', '|', 
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
            'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                'mediaEmbed', '|',
            'alignment', 'removeFormat', 'horizontalLine', 'specialCharacters', '|',
            'sourceEditing'
                    ]
                } )
                .then( editor => {
                    window.editor = editor;
                } )
                .catch( error => {
                    console.error( error );
                } ); 
        </script>
        <style>
.ck-editor__editable_inline {
    min-height: 200px;
}
</style>
 