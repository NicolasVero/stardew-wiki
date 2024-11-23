function get_site_root():string { 
    const protocol: string = window.location.protocol;
    const host: string = window.location.host;

    return (host === "localhost") 
        ? `${protocol}//localhost/travail/stardew_dashboard/` 
        : `${protocol}//stardew-dashboard.42web.io/`;
}

async function get_max_upload_size():Promise<number> {
	return fetch("./../includes/functions/utility_functions.php?action=get_max_upload_size")
		.then(response => response.json())
		.then((data: { post_max_size: number }) => {
			return data.post_max_size;
		}
    );
}
