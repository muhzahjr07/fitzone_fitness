<?php require __DIR__.'/../config.php';
$exists = $pdo->query("SELECT COUNT(*) c FROM users WHERE role='admin'")->fetch()['c'] ?? 0;
if(!$exists){
  $hash = password_hash('Admin@123', PASSWORD_DEFAULT);
  $pdo->prepare("INSERT INTO users(name,email,password,role,created_at) VALUES(?,?,?,?,NOW())")
      ->execute(['Site Admin','admin@fitzone.local',$hash,'admin']);
  echo "Admin user created: admin@fitzone.local / Admin@123";
} else { echo "Admin already exists."; }
