<?php 
include __DIR__.'/../includes/header.php'; 
require __DIR__.'/../config.php'; 
require __DIR__.'/../includes/upload.php'; 
require_role(['admin']);

// Display messages
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert success">'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert danger">'.htmlspecialchars($_SESSION['error']).'</div>';
    unset($_SESSION['error']);
}

$tab = $_GET['tab'] ?? 'classes';
// ... rest of your code ...
function post($k,$d=''){ return trim($_POST[$k] ?? $d); }
function get($k,$d=''){ return trim($_GET[$k] ?? $d); }

// ----- Delete handlers -----
if ($tab==='classes' && ($del = intval(get('delete')))) {
  $pdo->prepare("DELETE FROM classes WHERE id=?")->execute([$del]);
  header("Location: /dashboards/admin.php?tab=classes"); exit;
}
if ($tab==='trainers' && ($del = intval(get('delete')))) {
  $pdo->prepare("DELETE FROM trainers WHERE id=?")->execute([$del]);
  header("Location: /dashboards/admin.php?tab=trainers"); exit;
}
if ($tab==='memberships' && ($del = intval(get('delete')))) {
  $pdo->prepare("DELETE FROM memberships WHERE id=?")->execute([$del]);
  header("Location: /dashboards/admin.php?tab=memberships"); exit;
}
if ($tab==='blog' && ($del = intval(get('delete')))) {
  $pdo->prepare("DELETE FROM blog_posts WHERE id=?")->execute([$del]);
  header("Location: /dashboards/admin.php?tab=blog"); exit;
}
if ($tab==='gallery' && ($del = intval(get('delete')))) {
  $pdo->prepare("DELETE FROM gallery WHERE id=?")->execute([$del]);
  header("Location: /dashboards/admin.php?tab=gallery"); exit;
}
if ($tab==='bookings' && ($del = intval(get('delete')))) {
  $pdo->prepare("DELETE FROM bookings WHERE id=?")->execute([$del]);
  header("Location: /dashboards/admin.php?tab=bookings"); exit;
}
if ($tab==='users' && ($del = intval(get('delete')))) {
  // prevent self-deletion
  if ($del != $_SESSION['user']['id']) {
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$del]);
    $_SESSION['success'] = "User deleted successfully!";
  } else {
    $_SESSION['error'] = "You cannot delete yourself!";
  }
  header("Location: /dashboards/admin.php?tab=users"); 
  exit;
}

// ----- Create/Update handlers -----
if ($tab==='users' && $_SERVER['REQUEST_METHOD']==='POST') {
  $uid = intval($_POST['id']);
  $role = $_POST['role'];
  if ($uid != $_SESSION['user']['id'] && in_array($role, ['customer','staff','admin'])) {
    $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role,$uid]);
    $_SESSION['success'] = "User role updated successfully!";
  }
  header("Location: /dashboards/admin.php?tab=users");
  exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if ($tab==='classes') {
    $id = intval(post('id', 0));
    $img = handle_upload('image', 'classes', post('image_existing'));
    if ($id) {
      $pdo->prepare("UPDATE classes SET name=?,description=?,schedule=?,image_path=? WHERE id=?")
          ->execute([post('name'),post('description'),post('schedule'),$img,$id]);
    } else {
      $pdo->prepare("INSERT INTO classes(name,description,schedule,image_path) VALUES(?,?,?,?)")
          ->execute([post('name'),post('description'),post('schedule'),$img]);
    }
    header("Location: /dashboards/admin.php?tab=classes"); exit;
  }
  if ($tab==='trainers') {
    $id = intval(post('id', 0));
    $img = handle_upload('photo', 'trainers', post('photo_existing'));
    if ($id) {
      $pdo->prepare("UPDATE trainers SET name=?,specialization=?,bio=?,photo_path=? WHERE id=?")
          ->execute([post('name'),post('specialization'),post('bio'),$img,$id]);
    } else {
      $pdo->prepare("INSERT INTO trainers(name,specialization,bio,photo_path) VALUES(?,?,?,?)")
          ->execute([post('name'),post('specialization'),post('bio'),$img]);
    }
    header("Location: /dashboards/admin.php?tab=trainers"); exit;
  }
  if ($tab==='memberships') {
    $id = intval(post('id', 0));
    $img = handle_upload('image', 'memberships', post('image_existing'));
    if ($id) {
      $pdo->prepare("UPDATE memberships SET name=?,price=?,features=?,image_path=? WHERE id=?")
          ->execute([post('name'),post('price'),post('features'),$img,$id]);
    } else {
      $pdo->prepare("INSERT INTO memberships(name,price,features,image_path) VALUES(?,?,?,?)")
          ->execute([post('name'),post('price'),post('features'),$img]);
    }
    header("Location: /dashboards/admin.php?tab=memberships"); exit;
  }
  if ($tab==='blog') {
    $id = intval(post('id', 0));
    $img = handle_upload('image', 'blog', post('image_existing'));
    if ($id) {
      $pdo->prepare("UPDATE blog_posts SET title=?,category=?,content=?,image_path=? WHERE id=?")
          ->execute([post('title'),post('category'),post('content'),$img,$id]);
    } else {
      $pdo->prepare("INSERT INTO blog_posts(title,category,content,image_path,created_at) VALUES(?,?,?,?,NOW())")
          ->execute([post('title'),post('category'),post('content'),$img]);
    }
    header("Location: /dashboards/admin.php?tab=blog"); exit;
  }
  if ($tab==='gallery') {
    $id = intval(post('id', 0));
    $img = handle_upload('image', 'gallery', post('image_existing'));
    if ($id) {
      $pdo->prepare("UPDATE gallery SET caption=?,image_path=? WHERE id=?")
          ->execute([post('caption'),$img,$id]);
    } else {
      $pdo->prepare("INSERT INTO gallery(caption,image_path) VALUES(?,?)")
          ->execute([post('caption'),$img]);
    }
    header("Location: /dashboards/admin.php?tab=gallery"); exit;
  }
}
?>
<h2>Admin Dashboard</h2>
<p>
  <a style="text-decoration: none" class="color-orange <?= $tab === 'classes' ? 'active' : '' ?>" href="?tab=classes">Classes</a> |
  <a style="text-decoration: none" class="color-orange <?= $tab === 'trainers' ? 'active' : '' ?>" href="?tab=trainers">Trainers</a> |
  <a style="text-decoration: none" class="color-orange <?= $tab === 'memberships' ? 'active' : '' ?>" href="?tab=memberships">Memberships</a> |
  <a style="text-decoration: none" class="color-orange <?= $tab === 'blog' ? 'active' : '' ?>" href="?tab=blog">Blog</a> |
  <a style="text-decoration: none" class="color-orange <?= $tab === 'gallery' ? 'active' : '' ?>" href="?tab=gallery">Gallery</a> |
  <a style="text-decoration: none" class="color-orange <?= $tab === 'users' ? 'active' : '' ?>" href="?tab=users">Users</a>
</p>

<?php
function render_form_row($label,$html){ echo "<label>$label</label>$html"; }

// ---------- CLASSES ----------
if ($tab==='classes'):
  $edit = intval(get('edit',0));
  $item = ['id'=>0,'name'=>'','description'=>'','schedule'=>'','image_path'=>''];
  if ($edit) { $s=$pdo->prepare("SELECT * FROM classes WHERE id=?"); $s->execute([$edit]); $item=$s->fetch() ?: $item; }
?>
<div class="grid">
  <div class="card">
    <h3><?=$item['id']?'Edit':'Add'?> Class</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?=$item['id']?>">
      <input type="hidden" name="image_existing" value="<?=htmlspecialchars($item['image_path'])?>">
      <?php render_form_row('Name','<input name="name" value="'.htmlspecialchars($item['name']).'" required>'); ?>
      <?php render_form_row('Description','<textarea name="description" required>'.htmlspecialchars($item['description']).'</textarea>'); ?>
      <?php render_form_row('Schedule','<input name="schedule" value="'.htmlspecialchars($item['schedule']).'" required>'); ?>
      <?php render_form_row('Image','<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">'); ?>
      <button class="btn-primary"><?=$item['id']?'Update':'Add'?></button>
    </form>
  </div>
  <div class="card">
    <h3>All Classes</h3>
    <table>
      <tr><th>Image</th><th>Name</th><th>Schedule</th><th>Actions</th></tr>
      <?php foreach($pdo->query("SELECT * FROM classes ORDER BY id DESC") as $r): ?>
      <tr>
        <td><?php if($r['image_path']): ?><img src="<?=$r['image_path']?>" style="width:60px;height:40px;object-fit:cover;border-radius:6px"><?php endif; ?></td>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=htmlspecialchars($r['schedule'])?></td>
        <td>
          <a class = "color-orange" href="?tab=classes&edit=<?=$r['id']?>">Edit</a> |
          <a class="color-orange" href="?tab=classes&delete=<?=$r['id']?>" 
   onclick="return confirm('Delete this class?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php endif; ?>

<?php
// ---------- TRAINERS ----------
if ($tab==='trainers'):
  $edit = intval(get('edit',0));
  $item = ['id'=>0,'name'=>'','specialization'=>'','bio'=>'','photo_path'=>''];
  if ($edit) { $s=$pdo->prepare("SELECT * FROM trainers WHERE id=?"); $s->execute([$edit]); $item=$s->fetch() ?: $item; }
?>
<div class="grid">
  <div class="card">
    <h3><?=$item['id']?'Edit':'Add'?> Trainer</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?=$item['id']?>">
      <input type="hidden" name="photo_existing" value="<?=htmlspecialchars($item['photo_path'])?>">
      <?php render_form_row('Name','<input name="name" value="'.htmlspecialchars($item['name']).'" required>'); ?>
      <?php render_form_row('Specialization','<input name="specialization" value="'.htmlspecialchars($item['specialization']).'" required>'); ?>
      <?php render_form_row('Bio','<textarea name="bio" required>'.htmlspecialchars($item['bio']).'</textarea>'); ?>
      <?php render_form_row('Photo','<input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">'); ?>
      <button class="btn-primary"><?=$item['id']?'Update':'Add'?></button>
    </form>
  </div>
  <div class="card">
    <h3>All Trainers</h3>
    <table>
      <tr><th>Photo</th><th>Name</th><th>Specialization</th><th>Actions</th></tr>
      <?php foreach($pdo->query("SELECT * FROM trainers ORDER BY id DESC") as $r): ?>
      <tr>
        <td><?php if($r['photo_path']): ?><img src="<?=$r['photo_path']?>" style="width:60px;height:40px;object-fit:cover;border-radius:6px"><?php endif; ?></td>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=htmlspecialchars($r['specialization'])?></td>
        <td>
          <a class = "color-orange" href="?tab=trainers&edit=<?=$r['id']?>">Edit</a> |
       <a class="color-orange" href="?tab=trainers&delete=<?=$r['id']?>" 
   onclick="return confirm('Delete this trainer?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php endif; ?>

<?php
// ---------- MEMBERSHIPS ----------
if ($tab==='memberships'):
  $edit = intval(get('edit',0));
  $item = ['id'=>0,'name'=>'','price'=>'','features'=>'','image_path'=>''];
  if ($edit) { $s=$pdo->prepare("SELECT * FROM memberships WHERE id=?"); $s->execute([$edit]); $item=$s->fetch() ?: $item; }
?>
<div class="grid">
  <div class="card">
    <h3><?=$item['id']?'Edit':'Add'?> Membership</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?=$item['id']?>">
      <input type="hidden" name="image_existing" value="<?=htmlspecialchars($item['image_path'])?>">
      <?php render_form_row('Name','<input name="name" value="'.htmlspecialchars($item['name']).'" required>'); ?>
      <?php render_form_row('Price (LKR)','<input type="number" step="0.01" name="price" value="'.htmlspecialchars($item['price']).'" required>'); ?>
      <?php render_form_row('Features','<textarea name="features" required>'.htmlspecialchars($item['features']).'</textarea>'); ?>
      <?php render_form_row('Image','<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">'); ?>
      <button class="btn-primary"><?=$item['id']?'Update':'Add'?></button>
    </form>
  </div>
  <div class="card">
    <h3>All Memberships</h3>
    <table>
      <tr><th>Image</th><th>Name</th><th>Price</th><th>Actions</th></tr>
      <?php foreach($pdo->query("SELECT * FROM memberships ORDER BY id DESC") as $r): ?>
      <tr>
        <td><?php if($r['image_path']): ?><img src="<?=$r['image_path']?>" style="width:60px;height:40px;object-fit:cover;border-radius:6px"><?php endif; ?></td>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=number_format($r['price'],2)?></td>
        <td>
          <a class = "color-orange" href="?tab=memberships&edit=<?=$r['id']?>">Edit</a> |
          <a class="color-orange" href="?tab=memberships&delete=<?=$r['id']?>" 
   onclick="return confirm('Delete this plan?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php endif; ?>

<?php
// ---------- BLOG ----------
if ($tab==='blog'):
  $edit = intval(get('edit',0));
  $item = ['id'=>0,'title'=>'','category'=>'Workouts','content'=>'','image_path'=>''];
  if ($edit) { $s=$pdo->prepare("SELECT * FROM blog_posts WHERE id=?"); $s->execute([$edit]); $item=$s->fetch() ?: $item; }
  $cats = ['Workouts','Nutrition','Success Stories'];
?>
<div class="grid">
  <div class="card">
    <h3><?=$item['id']?'Edit':'Add'?> Blog Post</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?=$item['id']?>">
      <input type="hidden" name="image_existing" value="<?=htmlspecialchars($item['image_path'])?>">
      <?php render_form_row('Title','<input name="title" value="'.htmlspecialchars($item['title']).'" required>'); ?>
      <?php
        $opts = '';
        foreach($cats as $c){ $sel = $c===$item['category']?'selected':''; $opts.="<option $sel>$c</option>"; }
        render_form_row('Category','<select name="category">'.$opts.'</select>');
      ?>
      <?php render_form_row('Content','<textarea name="content" rows="8" required>'.htmlspecialchars($item['content']).'</textarea>'); ?>
      <?php render_form_row('Image','<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">'); ?>
      <button class="btn-primary"><?=$item['id']?'Update':'Publish'?></button>
    </form>
  </div>
  <div class="card">
    <h3>All Posts</h3>
    <table>
      <tr><th>Image</th><th>Title</th><th>Category</th><th>Published</th><th>Actions</th></tr>
      <?php foreach($pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC") as $r): ?>
      <tr>
        <td><?php if($r['image_path']): ?><img src="<?=$r['image_path']?>" style="width:60px;height:40px;object-fit:cover;border-radius:6px"><?php endif; ?></td>
        <td><?=htmlspecialchars($r['title'])?></td>
        <td><?=htmlspecialchars($r['category'])?></td>
        <td><?=htmlspecialchars($r['created_at'])?></td>
        <td>
          <a class = "color-orange" href="?tab=blog&edit=<?=$r['id']?>">Edit</a> |
          <a class="color-orange" href="?tab=blog&delete=<?=$r['id']?>" 
   onclick="return confirm('Delete this post?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php endif; ?>

<?php
// ---------- GALLERY ----------
if ($tab==='gallery'):
  $edit = intval(get('edit',0));
  $item = ['id'=>0,'caption'=>'','image_path'=>''];
  if ($edit) { $s=$pdo->prepare("SELECT * FROM gallery WHERE id=?"); $s->execute([$edit]); $item=$s->fetch() ?: $item; }
?>
<div class="grid">
  <div class="card">
    <h3><?=$item['id']?'Edit':'Add'?> Gallery Image</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?=$item['id']?>">
      <input type="hidden" name="image_existing" value="<?=htmlspecialchars($item['image_path'])?>">
      <?php render_form_row('Caption','<input name="caption" value="'.htmlspecialchars($item['caption']).'" required>'); ?>
      <?php render_form_row('Image','<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">'); ?>
      <button class="btn-primary"><?=$item['id']?'Update':'Add'?></button>
    </form>
  </div>
  <div class="card">
    <h3>Gallery</h3>
    <table>
      <tr><th>Image</th><th>Caption</th><th>Actions</th></tr>
      <?php foreach($pdo->query("SELECT * FROM gallery ORDER BY id DESC") as $r): ?>
      <tr>
        <td><?php if($r['image_path']): ?><img src="<?=$r['image_path']?>" style="width:60px;height:40px;object-fit:cover;border-radius:6px"><?php endif; ?></td>
        <td><?=htmlspecialchars($r['caption'])?></td>
        <td><a class="color-orange" href="?tab=gallery&delete=<?=$r['id']?>" 
   onclick="return confirm('Delete this image?');">Delete</a></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php endif; ?>

<?php
// ---------- BOOKINGS MGMT ----------
if ($tab==='bookings'):
  $rows = $pdo->query("SELECT b.id,u.name as uname,u.email,c.name as cname,c.schedule,b.created_at
                       FROM bookings b JOIN users u ON u.id=b.user_id JOIN classes c ON c.id=b.class_id
                       ORDER BY b.created_at DESC")->fetchAll();
?>
<div class="grid">
<div class="card">
  <h3>All Bookings</h3>
  <table>
    <tr><th>User</th><th>Email</th><th>Class</th><th>Schedule</th><th>Booked On</th><th>Action</th></tr>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['uname'])?></td>
        <td><?=htmlspecialchars($r['email'])?></td>
        <td><?=htmlspecialchars($r['cname'])?></td>
        <td><?=htmlspecialchars($r['schedule'])?></td>
        <td><?=htmlspecialchars($r['created_at'])?></td>
        <td><a class="color-orange" href="?tab=bookings&delete=<?=$r['id']?>" 
   onclick="return confirm('Cancel this booking?');">Cancel</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</div>
<?php endif; ?>

<?php
// ---------- USERS ----------
if ($tab==='users'):
?>
<div class="grid">
<div class="card">
  <h3>Users</h3>
  <table>
    <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
    <?php foreach($pdo->query("SELECT id,name,email,role FROM users ORDER BY id DESC") as $u): ?>
      <tr>
        <td><?=htmlspecialchars($u['name'])?></td>
        <td><?=htmlspecialchars($u['email'])?></td>
        <td>
	<?php if ($u['id'] != $_SESSION['user']['id']): ?>
          <form class = "tabledisplay" method="post">
            <input type="hidden" name="id" value="<?=$u['id']?>">
            <select name="role">
              <option value="customer" <?=$u['role']==='customer'?'selected':''?>>Customer</option>
              <option value="staff" <?=$u['role']==='staff'?'selected':''?>>Staff</option>
              <option value="admin" <?=$u['role']==='admin'?'selected':''?>>Admin</option>
            </select>
            <button class="btn-table" type="submit">Update</button>
          </form>
		<?php else: ?>
    		<em><?=htmlspecialchars($u['role'])?></em>
  		<?php endif; ?>
        </td>
        <td>
          <?php if ($u['id'] != $_SESSION['user']['id']): ?>
         <a class="color-orange" href="?tab=users&delete=<?=$u['id']?>" 
   onclick="return confirm('Delete this user?');">Delete</a>
          <?php else: ?>
            <em>(You)</em>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</div>
<?php endif; ?>


<?php include __DIR__.'/../includes/footer.php'; ?>
