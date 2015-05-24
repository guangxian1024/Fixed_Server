<header>

  <div class="masterhead">
    <div class="container-fluid">
      <div class="container">
        <div class="row-fluid">
          <div class="slogan" style="float:left">
			<img src ="/public/img/dash_mark.png" width = "100" height = "100"/>
		  </div>
       </div>
      </div>
     
    </div>
  </div>
  
  <style>
	.dash_menu{
		background-color:#EEEEEE;
		
	}
	.dash_downmenu{
		background-color:#00CCFF;
		hover:{background-color:#111188}
	}
	.dropdown-menu :hover{
		background-color: red;
	}
	
	.CategoryItem-Title{
		font-family:sans-serif;
	}
	.dropdown-menu{
		background-color:#EEEEEE;
	}
  </style>

  <!--masterhead-->
</header>
<div class="container dash_menu">
	<div class="navbar" >
		<ul class="nav navbar-nav" style="width:100%">
		<!--<div  class="nav navbar-nav" style="width:100%" id= "CategoryItems">-->
		<?php 
			foreach($category_list as $row)
			{
				echo'<li class="dropdown" id="CategoryItem_'.$row->id.'">
						<a href="#" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-expanded="false" style="padding-bottom:5px !important; padding-left:3px !important">
							<img src="/public/img/menu_imgs/menu_dashboard.png" width="35px"  style="padding-right:5px" /><label claas="CategoryItem-Title" id="category_title'.$row->id.'">'.$row->name .'</label><span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu" style= "min-width:150px; width:150px">
							<li><a href="/picturemanage/viewPictures?category='.$row->id.'">View</a></li>
							<li><a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever='. $row->id . ' >Edit</a></li>
							<li><a href="#" data-toggle="modal" class="CategoryDelete" id="'. $row->id . '">Delete</a></li>
						</ul>
					</li>';
			}
		?>               
		
			<li>
				<a  data-toggle="modal" data-target="#exampleModal" data-whatever="-1000" style="cursor:pointer">
					<img src="/public/img/addbackground.png" width="30px"/>Add Category
				</a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="/picturemanage/viewPictures?category='.$row->id.'">Add User</a></li>
					<li><a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever='. $row->id . ' >Edit</a></li>
				</ul>
			</li>
			<!--						
			<li class ="pull-right">
				<a href="#">	
					<img src="/public/img/menu_imgs/menu_push.png" width="30"/> Change Account
				</a>
			</li>
			-->
			<li class ="pull-right">
				<a data-toggle="modal" data-target="#editMessageModal" data-whatever="" style="cursor:pointer">	
					<img src="/public/img/menu_imgs/menu_push.png" width="30"/> Edit Message
				</a>
			</li>
		
		</ul>
	
		
	</div>
	
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop= "true" >
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" id="categoryID">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="exampleModalLabel">New Category</h4>
		  </div>
		  <div class="modal-body">
			<form role="form" id="categoryEditPanel">
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Title:</label>
				<input type="text" class="form-control" id="category-title">
			  </div>
			  <div class="form-group">
				<label for="message-text" class="control-label">Discription:</label>
				<textarea class="form-control" id="category-description"></textarea>
			  </div>
			  
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Price(£):</label>
				<!--<input type="text" class="form-control" id="category-price">-->
				<select id="category-price" name="category-price" style="width:100px">
					<option value="0" selected="selected" >Free</option>
					<option value="0.99">0.99</option>
				</select>
			  </div>
			  
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" id="savecategory">Save</button>
		  </div>
		</div>
	  </div>
	</div>

	<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop= "true" >
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" id="userID">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="exampleModalLabel">Edit User</h4>
		  </div>
		  <div class="modal-body">
			<form role="form" id="userEditPanel">
			  <div class="form-group">
				<label for="recipient-name" class="control-label">UserName:</label>
				<input type="text" class="form-control" id="user-name">
			  </div>
			  <div class="form-group">
				<label for="message-text" class="control-label">Password:</label>
				<input type="password" class="form-control" id="password">
			  </div>
			  
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Confirm Password:</label>
				<input type="password" class="form-control" id="confirm-password">
			  </div>
			  
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" id="saveUser">Save</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id="editMessageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop= "true" >
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="exampleModalLabel">Edit Message</h4>
		  </div>
		  <div class="modal-body">
			<form role="form" id="editMessagePanel">
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Message:</label>
				<textarea class="form-control" id="message" style="height:120px"></textarea>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" id="saveMessage">Save</button>
		  </div>
		</div>
	  </div>
	</div>
</div>

	
<script>

$('#exampleModal').on('show.bs.modal', function (event) {
 
  var button = $(event.relatedTarget); // Button that triggered the modal
  
  var categoryID = button.data('whatever') // Extract info from data-* attributes
  var modal = $(this);
	
	modal.find("#categoryID").val(categoryID);
	modal.find("#category-title").val("");
	modal.find("#category-description").val("");
	modal.find("#category-price").val("0");
	modal.find("#exampleModalLabel").text("New Category");
		
	if(categoryID == -1000)
	{
		$('#savecategory').text("Save");
		$('#savecategory').val("Save");
		
	}else
	{
		var url = "/picturemanage/getCategoryWithID";
		formData = [{name:"category-ID", value:categoryID}];
			
		$.ajax({
            url: url,
            type: "POST",
            dataType: "JSON",
            data: formData,
			    
            success: function(data) {
				modal.find("#category-title").val(data.name);
				modal.find("#category-description").val(data.description);
				modal.find("#category-price").val(data.price);
				return true;
            },
            error: function() {
				
            }
        });
		
		$("#exampleModalLabel").text("Edit Category");
		$('#savecategory').text("Update");
		$('#savecategory').val("Update");
	}
  
});

$('#editMessageModal').on('show.bs.modal', function (event) {
	var modal = $(this);
	modal.find("#message").text(message);
	
});

 $('#saveMessage').click(function(event){
	var modal = $(this);
	var tempMessage = $("#message").val();
		
	var formUrl = "/picturemanage/updateMessage";
	
	var formData = new FormData();
	formData.append("message",tempMessage);
	
	$.ajax({
			url: formUrl,
            type: "POST",
            dataType: "JSON",
            data: formData,
            contentType: !1,
            cache: !1,
            processData: !1,
            beforeSend: function() {
                //$(".loading-container").removeClass("hidden").show();
            },
            success: function(data) {
			    if(data.success == "ok")
				{
					message = tempMessage;
				}
			},
			error: function() {
            }
		});
		
	$('#editMessageModal').modal("hide");
});

  $('#savecategory').click(function(event){
	
	var tempValue = $(this).val();
	var categoryID = $("#categoryID").val();
	var formUrl;
	
	if(tempValue == "Save")
	{
		formUrl = "/picturemanage/createCategory";
	}else if(tempValue == "Update")
	{
		formUrl = "/picturemanage/updateCategory";
	}
	
	var category_title = $("#category-title").val();
	var category_description = $("#category-description").val();
	var category_price = $("#category-price").val();
	
	var formData = new FormData();
	
	formData.append("category-ID",categoryID);
	formData.append("category-title",category_title);
	formData.append("category-description",category_description);
	formData.append("category-price",category_price);
	
	//categoryID_Data = {name:"category-ID", value:categoryID};
	//fomrData.append(categoryID_Data);
		$.ajax({
			url: formUrl,
            type: "POST",
            dataType: "JSON",
            data: formData,
            contentType: !1,
            cache: !1,
            processData: !1,
            beforeSend: function() {
                //$(".loading-container").removeClass("hidden").show();
            },
            success: function(data) {
			    if(data.success == "create")
				{
					/* var categoryItem = '<li class="dropdown" id="CategoryItem_' + data.categoryID + '">' +
							'<a href="#" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-expanded="false" style="padding-bottom:5px !important; padding-left:3px !important" >' + 
								'<img src="/public/img/menu_imgs/menu_dashboard.png" width="35px"  style="padding-right:5px"/>'+ 
								 '<label id="category_title'+ data.categoryID + '">'+ category_title + '</label><span class="caret"></span>' + 
							'</a>' +
							'<ul class="dropdown-menu" role="menu">' +
								'<li><a href="/picturemanage/viewPictures?category='+ data.categoryID + '">View</a></li>' +
								'<li><a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever=' + data.categoryID +  ' >Edit</a></li>' + 
								'<li><a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever='+ data.categoryID + ' >Delete</a></li>' +
							'</ul>' +
							'</li>';
							
					$("#CategoryItems").append(categoryItem); */
					
					$('#exampleModal').modal("hide");
					
					window.open("/picturemanage/viewPictures?category="+data.categoryID,"_self");
				}else if(data.success == "update")
				{
					$('#category_title' + categoryID).text(category_title);
					$('#exampleModal').modal("hide");
				}
                return true;
            },
            error: function() {
            }
        })
	}); 
	
	$('.CategoryDelete').click(function(event){
		var categoryID = $(this).attr('id');
		var url = "/picturemanage/deleteCategory";
		
		var formData = new FormData();
		formData.append("category-ID",categoryID);
		
		$.ajax({
			url: url,
            type: "POST",
            dataType: "JSON",
            data: formData,
            contentType: !1,
            cache: !1,
            processData: !1,
            beforeSend: function() {
                //$(".loading-container").removeClass("hidden").show();
            },
            success: function(data) {
			    if(data.success == "ok")
				{
					window.open("/picturemanage/viewPictures?category="+data.categoryID,"_self");
				}
					//$("#CategoryItem_" + categoryID).remove();
			}
		});
	});
</script>

