<table id="datatable" class="display">
    <thead>
        <tr>
            <th>Type</th>
            <th>Titre</th>
            <th>Titre original</th>
            <th>Date de sortie</th>
            <th>Date</th>
            <th>Durée</th>
            <th>Résumé</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (!empty($productions)) {
                foreach ($productions as $production) {
                    echo "<td>$production->getType()</td>";
                    echo "<td>$production->getOriginalTitle() ?? $production->getTitle()</td>";
                    echo "<td>($production->getReleaseDate() ?? '')</td>";
                    echo "<td>($production->getRuntime() ?? '')</td>";
                    echo "<td>($production->getOverview() ?? '')</td>";
                }
            }
        ?>
    </tbody>
</table>
