import Button from "components/UIKit/Button";
import ButtonGroup from "components/UIKit/ButtonGroup";
import ButtonToolbar from "components/UIKit/ButtonToolbar";

/**
 * Paginator button group component for pagination of data lists.
 */
export default function Paginator({
  pageCount,
  currentPage,
  setCurrentPage,
  className,
}: {
  pageCount: number;
  currentPage: number;
  setCurrentPage: (page: number) => void;
  className?: string;
}) {
  const StartButtons = ({ size = 2 }: { size?: number }) => {
    const startButtons = [];
    for (let i = 1; i <= size; i++) {
      startButtons.push(
        <Button
          key={i}
          onClick={() => setCurrentPage(i)}
          active={i === currentPage}
        >
          {i}
        </Button>
      );
    }
    return <>{startButtons}</>;
  };

  const MiddleButtons = ({ size = 3 }: { size?: number }) => {
    const middleButtons = [];

    let start = Math.max(currentPage - Math.floor(size / 2), size);
    const end = Math.min(start + size, pageCount - Math.ceil(size / 2));
    start = Math.max(end - size, size);
    for (let i = start; i < end; i++) {
      middleButtons.push(
        <Button
          onClick={() => setCurrentPage(i)}
          active={i === currentPage}
          key={i}
        >
          {i}
        </Button>
      );
    }
    return <>{middleButtons}</>;
  };

  const EndButtons = ({ size = 2 }: { size?: number }) => {
    const endButtons = [];
    for (let i = pageCount - size; i < pageCount; i++) {
      endButtons.push(
        <Button
          onClick={() => setCurrentPage(i)}
          active={i === currentPage}
          key={i}
        >
          {i}
        </Button>
      );
    }
    return <>{endButtons}</>;
  };

  if (pageCount > 7) {
    return (
      <ButtonToolbar className={className}>
        <ButtonGroup className="me-2">
          <StartButtons />
        </ButtonGroup>
        <ButtonGroup className="me-2">
          <MiddleButtons />
        </ButtonGroup>
        <ButtonGroup className="me-2">
          <EndButtons />
        </ButtonGroup>
      </ButtonToolbar>
    );
  } else
    return (
      <ButtonToolbar className={className}>
        <ButtonGroup>
          {Array(pageCount)
            .fill(0)
            .map((_, index) => (
              <Button
                key={index + 1}
                onClick={() => setCurrentPage(index + 1)}
                active={currentPage === index + 1}
              >
                {index + 1}
              </Button>
            ))}
        </ButtonGroup>
      </ButtonToolbar>
    );
}
