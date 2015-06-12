<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Votre bateau</title>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/marina.css" rel="stylesheet">
</head>

<body>
    <div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">


            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Marinade</a>
            </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Repas <span class="sr-only">(current)</span></a></li>
                    <li><a href="#">Paramètres</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Déconnexion</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <div class="container">
    <h2>Le Temeraire <small>Frégate</small></h2>
    <table class="table table-bordered" id="table-repas">
        <thead><th></th>         <th>Lundi</th>    <th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th><th>Dimanche</th></thead>
        <tbody><tr><th>Déjeuner</th> <td></td>     <td class="success">Jeremy<ul><li>Melon</li><li>Risotto aux légumes</li><li>Crêpes</li></ul></td>        <td></td>     <td></td>        <td></td>      <td></td><td></td></tr>
               <tr><th>Dîner</th>    <td></td>     <td class="success">Baptiste<ul><li>Salade de riz</li><li>Œufs pochés</li><li>Fondant</li></ul></td>        <td class="success">Nico<ul><li>Tomates</li><li>Entrecôtes</li><li>Salade de fruit</li></ul></td>     <td></td>        <td></td>      <td></td><td></td></tr>
        </tbody>
    </table>
    <button type="button" class="btn">Liste des ingrédients</button>
    </div>

    <div class="modal fade" id="placement-repas-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Placer un repas</h4>
                </div>
                <div class="modal-body">
                    <div id="placer-repas" title="Placer un nouveau repas" class="form-group" >
                        <h4>Composition</h4>
                        <ul class="list-unstyled">
                            <li>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Melon <span class="caret"></span></button>
                                    <ul class="dropdown-menu list-group">
                                        <li class="list-group-item"><a href="#"><span class="badge">1€</span> Melon</a></li>
                                        <li class="list-group-item"><a href="#"><span class="badge">3€</span> Salade de riz</a></li>
                                        <li class="list-group-item"><a href="#"><span class="badge">1€</span> Tomates à la vinaigrette</a></li>
                                        <li class="list-group-item"><a href="#"><span class="badge">1€</span> Concombres au yaourt</a></li>
                                        <li class="list-group-item"><a href="#"><span class="badge">3€</span> Galette au sarrasin</a></li>
                                        <li class="list-group-item"><a href="#"><span class="badge">2€</span> Salade Niçoise</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Risotto <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Bœuf Bourguignon</a></li>
                                        <li><a href="#">Entrecôte</a></li>
                                        <li><a href="#">Risotto</a></li>
                                        <li><a href="#">Foundue au vacherin</a></li>
                                        <li><a href="#">Œufs pochés</a></li>
                                        <li><a href="#">Omelette roulée aux fines herbes</a></li>
                                        <li><a href="#">Lasagnes aux légumes</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Riz au lait <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Charlotte aux framboises</a></li>
                                        <li><a href="#">Riz au lait</a></li>
                                        <li><a href="#">Flan au caramel</a></li>
                                        <li><a href="#">Mousse au chocolat</a></li>
                                        <li><a href="#">Crêpes soufflées</a></li>
                                        <li><a href="#">Gaufres flambées</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary">Enregistrer</button>
            </div>
            </div>
        </div>
    </div>
    </div>

    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/modal.js"></script>

    <script>
    $(function(){
        $('.dropdown-toggle').dropdown();
        $('#table-repas td').click(function(){
            $('#placement-repas-modal').modal();
        });
    });
    </script>
</body>
