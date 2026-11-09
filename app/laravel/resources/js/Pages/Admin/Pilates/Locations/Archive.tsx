import { LocationList } from "./Index";
import type { Location } from "./LocationForm";

export default function LocationsArchive({ locations }: { locations: Location[] }) {
    return <LocationList locations={locations} archive />;
}
