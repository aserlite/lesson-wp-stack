const { registerBlockType } = wp.blocks;
const { TextControl } = wp.components;

registerBlockType('custom/portfolio-specific-block', {
    title: 'Projet Spécifique',
    icon: 'shield',
    category: 'common',
    attributes: {
        projectId: {
            type: 'string',
            default: '',
        },
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;

        const onProjectIdChange = (newProjectId) => {
            setAttributes({ projectId: newProjectId });
        };

        return (
            <div>
                <h3>Configurer le Projet</h3>
                <TextControl
                    label="ID du Projet"
                    value={attributes.projectId}
                    onChange={onProjectIdChange}
                />
            </div>
        );
    },
    save: function() {
        return null;
    },
});
