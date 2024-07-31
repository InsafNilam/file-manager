import { useState } from "react";
import FileDialog from "./FileDialog";
import PropTypes from "prop-types";
import FileTemplate from "./FileTemplate";

export default function FileInput({
  selectedFiles = [],
  documents = [],
  onFileChange,
  apiUrl,
  multiple = false,
  previewTitle = "File Preview",
}) {
  const [files, setFiles] = useState(selectedFiles);

  const handleFileRemove = (file) => {
    const updatedFiles = files.filter((f) => f.path !== file.path);
    setFiles(updatedFiles);
    onFileChange(updatedFiles);
  };

  return (
    <div className="w-full flex flex-col p-2 border-2 border-dashed rounded-md gap-1">
      <div>{previewTitle}</div>
      <div className="h-40 space-y-2 mb-1">
        <div className="snap-mandatory snap-x flex flex-row h-3/4 gap-2 overflow-x-scroll hide-scroll-bar flex-nowrap">
          {files.map((file, index) => (
            <div className="inline-block snap-center" key={index}>
              <div className="w-64 h-full max-w-xs overflow-hidden rounded-md bg-gray-200 relative">
                {/* Temporarily made File Template not to be deleted */}
                <FileTemplate
                  type={file.type}
                  name={file.name}
                  url={file.path}
                  handleFileDelete={() => handleFileRemove(file)}
                  isSelected={true}
                />
              </div>
            </div>
          ))}
        </div>
        <div className="grid place-content-end">
          <FileDialog
            selectedFiles={files}
            setSelectedFiles={(files) => {
              setFiles(files);
              onFileChange(files);
            }}
            apiUrl={apiUrl}
            multiple={multiple}
            documents={documents}
          />
        </div>
      </div>
    </div>
  );
}

FileInput.propTypes = {
  selectedFiles: PropTypes.arrayOf(PropTypes.object),
  documents: PropTypes.object.isRequired,
  onFileChange: PropTypes.func.isRequired,
  apiUrl: PropTypes.string.isRequired,
  placeholder: PropTypes.string,
  previewTitle: PropTypes.string,
  multiple: PropTypes.bool,
};
