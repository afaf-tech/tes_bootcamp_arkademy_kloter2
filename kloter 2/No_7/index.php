<?php require_once 'header.php' ?>
    <?php
	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$deleted = $id;
		$result = mysqli_query($db, "DELETE FROM nama WHERE id=$id");
	}
	if(isset($_POST['add'])){
		$name = $_POST['name'];
		$hobby = $_POST['hobby'];
		$category = $_POST['category'];
        $result = mysqli_query($db, "SELECT name from nama where name='$name'");
		if(mysqli_fetch_array($result)){
			$existed = $name;
		}else{
			$added = $name;
			$result = mysqli_query($db, "INSERT INTO nama(name,id_hobby,id_category) VALUES('$name',$hobby,$category)");
		}
	}
	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$hobby = $_POST['hobby'];
		$category = $_POST['category'];
		$edited = $id;
		$result = mysqli_query($db, "UPDATE nama SET name='$name',id_hobby=$hobby,id_category=$category WHERE id=$id");
	}
?>
        <script>
            var hobbies = [<?php $result = mysqli_query($db, "SELECT
																	hobi.id AS hobby_id,
																	hobi.name AS hobby,
																	kategori.name AS category,
																	kategori.id AS category_id
																FROM
																	hobi
																JOIN kategori ON hobi.id_category = kategori.id ");
						while($data = mysqli_fetch_array($result)):	?>		
				{ 	"hobby_id": "<?= $data['hobby_id'] ?>",
					"hobby": "<?= $data['hobby'] ?>",
					"category": "<?= $data['category'] ?>",
					"category_id": "<?= $data['category_id'] ?>",
				},
			<?php endwhile ?>
			];
            $(document).ready(function() {
                $('#deleteDataModal').on('show.bs.modal', function(e) {
                    var id = $(e.relatedTarget).data('id');
                    $(e.currentTarget).find('input[name="id"]').val(id);
                });
                
				var category = hobbies[0]['category'];
				var category_id = hobbies[0]['category_id'];
				var addCategory = document.getElementById('addCategory');
				addCategory.innerHTML = "<option value="+category_id+">"+category+"</option>";
				
				var addCategory2 = document.getElementById('addCategory2');
				addCategory2.innerHTML = "<option value="+category_id+">"+category+"</option>";
				
				var addhobby = document.getElementById('addHobby');
				addhobby.onchange = function(){
					var value = parseInt(addhobby.value) - 1;
					category = hobbies[value]['category'];
					category_id = hobbies[value]['category_id'];
					addCategory.innerHTML = "<option value="+category_id+">"+category+"</option>";
				};
				for(let i = 0;i < hobbies.length;i++){
					addHobby.innerHTML += "<option value="+hobbies[i]['hobby_id']+">"+hobbies[i]['hobby']+"</option>";
				}
				
				
				var addhobby2 = document.getElementById('addHobby2');
				addhobby2.onchange = function(){
					var value = parseInt(addhobby2.value) - 1;
					category = hobbies[value]['category'];
					category_id = hobbies[value]['category_id'];
					addCategory2.innerHTML = "<option value="+category_id+">"+category+"</option>";
				};
				
				for(let i = 0;i < hobbies.length;i++){
					addHobby2.innerHTML += "<option value="+hobbies[i]['hobby_id']+">"+hobbies[i]['hobby']+"</option>";
				}
				
                $('#editDataModal').on('show.bs.modal', function(e) {
                    var id = $(e.relatedTarget).data('id');
                    $(e.currentTarget).find('input[name="id"]').val(id);
                    var name = $(e.relatedTarget).data('name');
                    $(e.currentTarget).find('input[name="name"]').val(name);
                    var hobby = $(e.relatedTarget).data('hobby');
					$(e.currentTarget).find('select[name="hobby"]').val(hobby);
					var value = parseInt(addhobby2.value) - 1;
					category = hobbies[value]['category'];
					category_id = hobbies[value]['category_id'];
					addCategory2.innerHTML = "<option value="+category_id+">"+category+"</option>";
                });
            });
        </script>

        <body>
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>ARKADEMY <b>BOOTCAMP</b></h2>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="table-wrapper">
                    <table class="table table-hover">
                        <div class="add">
                            <a href="#addDataModal" class="btn btn-warning" data-toggle="modal"><span>Add</span></a>
                        </div>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Hobby</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
						$result = mysqli_query($db, "SELECT 	nama.id AS id,
																nama.name     AS name, 
																   hobi.name     AS hobby, 
																   hobi.id     AS id_hobby, 
																   kategori.id AS id_category ,
																   kategori.name AS category 
															FROM   nama 
																   JOIN hobi 
																	 ON nama.id_hobby = hobi.id 
																   JOIN kategori 
																	 ON nama.id_category = kategori.id ");
						while($data = mysqli_fetch_array($result)):
					?>
                                <tr>
                                    <td>
                                        <?= $data['name'] ?>
                                    </td>
                                    <td>
                                        <?= $data['hobby'] ?>
                                    </td>
                                    <td>
                                        <?= $data['category'] ?>
                                    </td>
                                    <td>
                                        <a href="#editDataModal" class="edit" data-toggle="modal" data-id="<?= $data['id']; ?>" data-name="<?= $data['name'] ?>" data-hobby="<?= $data['id_hobby'] ?>" data-category="<?= $data['id_category'] ?>">
                                            <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                                        </a>
                                        <a href="#deleteDataModal" class="delete" data-toggle="modal" data-id="<?= $data['id']; ?>">
                                            <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Add Modal HTML -->
            <div id="addDataModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="add" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Data</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Hobby</label>
                                    <select id="addHobby" name="hobby" class="form-control">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select id="addCategory" name="category" class="form-control" readonly>
                                        <option value=0>--- Please Select Hobby ----</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-warning" value="Add" name="add">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Exist Modal HTML -->
            <div id="existModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Already Exists <i class="material-icons">error</i></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Data dengan Nama: <?= $existed ?> sudah ada</p>
                            </div>
                    </div>
                </div>
            </div>
            <!-- Added Modal HTML -->
            <div id="addedModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Added Successfully <i class="material-icons">check_circle</i></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Data dengan Nama: <?= $added ?> berhasil ditambahkan</p>
                            </div>
                    </div>
                </div>
            </div>
            <!-- Edited Modal HTML -->
            <div id="editedModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Edited Successfully <i class="material-icons">check_circle</i></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Data dengan ID: <?= $edited ?> berhasil diubah</p>
                            </div>
                    </div>
                </div>
            </div>
            <!-- Edited Modal HTML -->
            <div id="deletedModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Deleted Successfully <i class="material-icons">check_circle</i></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Data dengan ID: <?= $deleted ?> berhasil dihapus</p>
                            </div>
                    </div>
                </div>
            </div>
            <!-- Edit Modal HTML -->
            <div id="editDataModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="edit" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Data</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Hobby</label>
                                    <select id="addHobby2" name="hobby" class="form-control">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select id="addCategory2" name="category" class="form-control" readonly>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="id">
                                <input type="submit" class="btn btn-warning" value="Edit" name="edit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Delete Modal HTML -->
            <div id="deleteDataModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="delete" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="modal-header">
                                <h4 class="modal-title">Delete Data</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah anda yakin ingin menghapus data?</p>
                                <p class="text-warning"><small>Data yang dihapus tidak dapat kembali.</small></p>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="id">
                                <input type="submit" class="btn btn-danger" value="Delete" name="delete">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </body>

        </html>
		<?php 
		if(isset($existed)){
			echo("<script>$('#existModal').modal('show');</script>");
		}  else if(isset($added)){
			echo("<script>$('#addedModal').modal('show');</script>");
		}  else if(isset($edited)){
			echo("<script>$('#editedModal').modal('show');</script>");
		}  else if(isset($deleted)){
			echo("<script>$('#deletedModal').modal('show');</script>");
		} 
		?>