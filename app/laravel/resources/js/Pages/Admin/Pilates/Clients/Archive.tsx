import ClientList, { type AccountPage, type StandaloneClientPage } from "./ClientList";

export default function ClientsArchive({ clients, standaloneClients }: {
    clients: AccountPage; standaloneClients: StandaloneClientPage;
}) {
    return <ClientList page={clients} standaloneClients={standaloneClients} archived />;
}
