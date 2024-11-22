export function get_site_root(): string { 
    const protocol: string = window.location.protocol;
    const host: string = window.location.host;

    return (host === "localhost") 
        ? `${protocol}//localhost/travail/stardew_dashboard/` 
        : `${protocol}//stardew-dashboard.42web.io/`;
}