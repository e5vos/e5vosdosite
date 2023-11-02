// https://medium.com/@kch062522/useconfirm-a-custom-react-hook-to-prompt-confirmation-before-action-f4cb746ebd4e
import { FC, useState } from "react";

export type ConfirmDialogProps = {
    handleConfirm: () => void;
    handleCancel: () => void;
};
const useConfirm = (Dialog: FC<ConfirmDialogProps>) => {
    const [promise, setPromise] = useState<{
        resolve: (v: boolean | PromiseLike<boolean>) => void;
    } | null>(null);
    const confirm = () =>
        new Promise<boolean>((resolve, reject) => {
            setPromise({ resolve });
        });

    const handleConfirm = () => {
        promise?.resolve(true);
        setPromise(null);
    };

    const handleCancel = () => {
        promise?.resolve(false);
        setPromise(null);
    };

    const wrappedDialog = () => (
        <Dialog handleConfirm={handleConfirm} handleCancel={handleCancel} />
    );
    return [wrappedDialog, confirm] as const;
};

export default useConfirm;
