import { Dialog as DialogHeadless } from "@headlessui/react";
import { ReactNode } from "react";

import Locale from "lib/locale";

import Button from "./Button";
import Loader from "./Loader";

const locale = Locale({
  hu: {
    close: "Bezárás"
  },
  en: {
    close: "Close"
  }
});

const Scroller = ({ children }: { children: ReactNode }) => {
  return <div className="h-[500px]  overflow-auto scroller">{children}</div>;
};

const Dialog = ({
  open,
  onClose,
  title,
  description,
  children,
  isLoading = false
}: {
  open: boolean;
  title?: string;
  description?: string;
  children?: React.ReactNode;
  onClose: () => void;
  isLoading?: boolean;
}) => {
  return (
    <DialogHeadless open={open} onClose={onClose} className="relative z-50">
      <div className="fixed inset-0 bg-gray-500/50" aria-hidden="true" />

      <div className="fixed inset-0 flex items-center justify-center p-4">
        <DialogHeadless.Panel className="mx-auto min-w-[200px] max-w-[50%] rounded-3xl border-8 border-gray-800/50 bg-gray-100 p-3 shadow-2xl  shadow-gray-800">
          {isLoading ? (
            <Loader />
          ) : (
            <div className="">
              <div className="mx-4 mb-4 text-center">
                {title && (
                  <DialogHeadless.Title className="text-lg font-bold">
                    {title}
                  </DialogHeadless.Title>
                )}
                {description && (
                  <DialogHeadless.Description className="mb-3 text-justify">
                    {description}
                  </DialogHeadless.Description>
                )}
                {children}
              </div>
            </div>
          )}
          <div className="mx-auto text-center">
            <Button onClick={onClose} variant="danger">
              {locale.close}
            </Button>
          </div>
        </DialogHeadless.Panel>
      </div>
    </DialogHeadless>
  );
};

export default Object.assign(Dialog, { Scroller });
