import { Location } from "types/models";

import locationAPI from "lib/api/locationAPI";

import Form from "components/UIKit/Form";
import Loader from "components/UIKit/Loader";

const elementName = (e: Location) => `${e.floor} - ${e.name}`;

const LocationSearchCombobox = ({
    onChange,
    initialValue,
}: {
    onChange: (value: Location) => any;
    initialValue?: Location;
}) => {
    const { data: options } = locationAPI.useGetLocationsQuery();

    if (!options) return <Loader />;
    return (
        <div className="max-w-sm">
            <Form.ComboBox
                options={options ?? []}
                initialValue={initialValue ? elementName(initialValue) : ""}
                getElementName={elementName}
                onChange={(e) => {
                    if (!e) return;
                    onChange(e);
                }}
                renderElement={(e) => (
                    <span className="mt-3">{elementName(e)}</span>
                )}
                filter={(s) => (e) =>
                    elementName(e)
                        .toLocaleLowerCase()
                        .startsWith(s.toLowerCase())
                }
            />
        </div>
    );
};

export default LocationSearchCombobox;
