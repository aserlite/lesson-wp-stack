// js/portfolio-block-editor.js
wp.blocks.registerBlockType( 'custom/portfolio-block', {
    title: 'Last projects',
    icon: 'portfolio', // Remplacez-le par l'icône de votre choix
    category: 'common',
    edit: function() {
        // Code d'édition du bloc dans l'éditeur
        return null;
    },
    save: function() {
        // Code de sauvegarde du bloc
        return null;
    },
} );
