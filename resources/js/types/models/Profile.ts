export interface Profile {
  id: number;
  legacy_id: number | null;

  name: string | null;
  name_latin: string | null;
  ethnicity: string | null;
  location: string | null;

  age: number | null;
  year_of_birth: number | null;
  date_of_birth: string | null;

  height: number | null;
  weight: number | null;
  measurement_size: number | null;

  position: string | null;
  preferences: string | null;

  has_face_photo: boolean;
  has_body_photo: boolean;
  has_dick_photo: boolean;
  has_anus_photo: boolean;
  has_cum_photo: boolean;
  has_had_sex: boolean;

  phone_number: string | null;
  social_profiles: string | null;

  comment: string | null;
  extra: Record<string, unknown> | null;
}