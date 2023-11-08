import { dumpState } from "hooks/useStateDump";
import { useEffect, useState } from "react";
import QRCode from "react-qr-code";

import adminAPI from "lib/api/adminAPI";
import { useSelector } from "lib/store";

import Button from "components/UIKit/Button";
import Dialog from "components/UIKit/Dialog";
import Form from "components/UIKit/Form";

const DebugDump = () => {
    const state = useSelector(dumpState);
    const [dump] = adminAPI.useDumpStateMutation();
    const [open, setOpen] = useState(false);
    const [key, setKey] = useState("");
    const [statestr, setStatestr] = useState("");

    useEffect(() => {
        (window as any).dump = async () => {
            setOpen(true);
        };
    }, []);

    useEffect(() => {
        if (!open) return;
        setStatestr(JSON.stringify(state).substring(0, 2000));
    }, [open, state]);

    return (
        <Dialog open={open} onClose={() => setOpen(false)}>
            {open && (
                <div>
                    <QRCode value={statestr} className="mx-auto my-4" />
                    <div className="flex flex-row gap-4">
                        <Form.Control
                            onChange={(t) => setKey(t.target.value)}
                        />
                        <Button
                            onClick={async () => {
                                console.log("dumping", state);
                                await dump({
                                    dump: state,
                                    key: key !== "" ? key : undefined,
                                });
                            }}
                        >
                            Dump
                        </Button>
                    </div>
                </div>
            )}
        </Dialog>
    );
};

export default DebugDump;
