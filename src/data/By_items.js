import { useState, useEffect } from 'react';
import { __ } from "@wordpress/i18n";

function By_items() {
    const [by_items, setBy_items] = useState([{'value': '-1', 'label':__("Loading...", "husky-block")}]);
    const [error, setError] = useState('');

    useEffect(() => {
        async function fetchData() {
            try {
                const formData = new FormData();
                formData.append('action', 'get_all_by_items');
                const result = await fetch(ajaxurl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                });
                const by_items = await result.json();
                
                setBy_items(by_items);
            } catch (error) {
                setError(error.message);
            }
            
        }
        fetchData();
    }, []);

    if (error) {
        return [{"value": "-1", "laber": {error}}];
    }
    return by_items;
}

export default By_items;