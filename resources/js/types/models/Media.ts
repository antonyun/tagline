export interface Media {
  id: number;
  filename: string;
  original_name: string | null;
  mime_type: string | null;
  size: number | null;
  md5: string | null;
  sha256: string | null;
  thumbnail: string | null;
  relative_path: string | null;
  relative_thumbnail_path: string | null;
}