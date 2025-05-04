<?php
require __DIR__ . '/../../config/config.php';

$mois = intval($_GET['mois']);
$annee = intval($_GET['annee']);

$sql = "SELECT m.*, td.nom AS document 
        FROM mouvements m 
        JOIN types_documents td ON m.type_document_id = td.id 
        WHERE MONTH(m.date_mouvement) = $mois AND YEAR(m.date_mouvement) = $annee 
        ORDER BY m.date_mouvement ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Impression des Mouvements</title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style> 
        <link href="../vendor/bootstrapc/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../vendor/aos/aos.css" rel="stylesheet">
    <link href="../vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
</head>
<body onload="window.print()">

<h2>Mouvements - <?php echo date('F Y', mktime(0, 0, 0, $mois, 10, $annee)); ?></h2>

<table class="table table-bordered table-striped mt-3">
<thead>
        <tr>
            <th>Date</th>
            <th>Document</th>
            <th>Type</th>
            <th>Quantité</th>
            <th>Responsable</th>
            <th>Destinataire</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo date('d/m/Y H:i', strtotime($row['date_mouvement'])); ?></td>
                <td><?php echo $row['document']; ?></td>
                <td><?php echo ucfirst($row['type_mouvement']); ?></td>
                <td><?php echo $row['quantite']; ?></td>
                <td><?php echo $row['responsable']; ?></td>
                <td><?php echo $row['destinataire']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
