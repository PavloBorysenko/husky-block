import { useState, useEffect } from 'react';
import { __ } from "@wordpress/i18n";

function Taxonomies() {
    const [taxonomies, setTaxonomies] = useState([{'value': '-1', 'label':__("Loading...", "husky-block")}]);
    const [error, setError] = useState('');

    useEffect(() => {
        async function fetchData() {
            try {
                const formData = new FormData();
                formData.append('action', 'get_all_tax_items');
                const result = await fetch(ajaxurl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                });
                const taxonomies = await result.json();
                
                setTaxonomies(taxonomies);
            } catch (error) {
                setError(error.message);
            }
            
        }
        fetchData();
    }, []);

    if (error) {
        return [{"value": "-1", "laber": {error}}];
    }
    return taxonomies;
}

export default Taxonomies;