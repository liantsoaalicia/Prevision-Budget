<section id="ajout-categorie">
    <form action="traitement-ajout-categorie.php" method="POST">
        <div>
            <label for="categorie">Catégorie :</label>
            <select id="categorie" name="categorie" required>
                <option value="Depense">Dépense</option>
                <option value="Recette">Recette</option>
            </select>
        </div>

        <div>
            <label for="types">Types :</label>
            <input type="text" id="types" name="types" required>
        </div>

        <div>
            <label for="nature">Nature :</label>
            <input type="text" id="nature" name="nature" required>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</section>