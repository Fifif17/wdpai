PGDMP  ,                     |            fiszlet    16.1 (Debian 16.1-1.pgdg120+1)    16.1 P               0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    16384    fiszlet    DATABASE     r   CREATE DATABASE fiszlet WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';
    DROP DATABASE fiszlet;
                postgres    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
                pg_database_owner    false            �           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                   pg_database_owner    false    4            �            1255    16658 >   add_word_to_set(character varying, character varying, integer)    FUNCTION     R  CREATE FUNCTION public.add_word_to_set(p_word_en character varying, p_word_pl character varying, p_set_id integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_word_id INT;
BEGIN
    -- Insert the word into the Words table if it doesn't already exist
    INSERT INTO Words (word_en, word_pl)
    VALUES (p_word_en, p_word_pl)
    ON CONFLICT ON CONSTRAINT unique_word_combination DO NOTHING;

    -- Retrieve the word_id for the existing or newly inserted word
    SELECT id_word INTO v_word_id
    FROM Words
    WHERE word_en = p_word_en AND word_pl = p_word_pl
    LIMIT 1;

    -- Insert the word_id and set_id into the Word_in_sets table if the entry doesn't already exist
    INSERT INTO Word_in_sets (id_word, id_set)
    VALUES (v_word_id, p_set_id)
    ON CONFLICT ON CONSTRAINT unique_word_set_combination DO NOTHING;
END;
$$;
 r   DROP FUNCTION public.add_word_to_set(p_word_en character varying, p_word_pl character varying, p_set_id integer);
       public          postgres    false    4            �            1255    16656 H   insert_into_sets(character varying, character varying, integer, integer)    FUNCTION     6  CREATE FUNCTION public.insert_into_sets(p_name character varying, p_image character varying, p_word_count integer, p_id_author integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO Sets (name, image, word_count, id_author)
    VALUES (p_name, p_image, p_word_count, p_id_author);
END;
$$;
 �   DROP FUNCTION public.insert_into_sets(p_name character varying, p_image character varying, p_word_count integer, p_id_author integer);
       public          postgres    false    4            �            1255    16524 %   insert_user_history(integer, integer)    FUNCTION     �   CREATE FUNCTION public.insert_user_history(p_user_id integer, p_set_id integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO User_history (id_user, id_set) VALUES (p_user_id, p_set_id);
END;
$$;
 O   DROP FUNCTION public.insert_user_history(p_user_id integer, p_set_id integer);
       public          postgres    false    4            �            1255    16666 $   remove_set_and_associations(integer)    FUNCTION     �  CREATE FUNCTION public.remove_set_and_associations(p_set_id integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_word_ids INT[];
BEGIN
    -- Retrieve all word_ids associated with the set
    SELECT ARRAY(SELECT id_word FROM Word_in_sets WHERE id_set = p_set_id) INTO v_word_ids;

    -- Delete associations in Word_in_sets for the specified set
    DELETE FROM Word_in_sets
    WHERE id_set = p_set_id;

    -- Delete records from user_history
    DELETE FROM user_history
    WHERE id_set = p_set_id;

    -- Delete the set from Sets
    DELETE FROM Sets
    WHERE id_set = p_set_id;

    -- Delete the words associated with the set from Words
    DELETE FROM Words
    WHERE id_word = ANY(v_word_ids);
END;
$$;
 D   DROP FUNCTION public.remove_set_and_associations(p_set_id integer);
       public          postgres    false    4            �            1255    16667 K   remove_word_and_associations_by_en_pl(character varying, character varying)    FUNCTION     (  CREATE FUNCTION public.remove_word_and_associations_by_en_pl(p_word_en character varying, p_word_pl character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_word_id INT;
BEGIN
    -- Retrieve the word_id for the specified word
    SELECT id_word INTO v_word_id
    FROM Words
    WHERE word_en = p_word_en AND word_pl = p_word_pl
    LIMIT 1;

    -- If the word exists, delete its associations and the word itself
    IF v_word_id IS NOT NULL THEN
        -- Delete associations in Word_in_sets
        DELETE FROM Word_in_sets
        WHERE id_word = v_word_id;

        -- Delete the word from Words
        DELETE FROM Words
        WHERE id_word = v_word_id;
    ELSE
        RAISE EXCEPTION 'Word with combination (%, %) does not exist.', p_word_en, p_word_pl;
    END IF;
END;
$$;
 v   DROP FUNCTION public.remove_word_and_associations_by_en_pl(p_word_en character varying, p_word_pl character varying);
       public          postgres    false    4            �            1255    16665 +   remove_word_and_associations_by_id(integer)    FUNCTION     C  CREATE FUNCTION public.remove_word_and_associations_by_id(p_id_word integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- If the word exists, delete its associations and the word itself
    IF EXISTS (SELECT 1 FROM Words WHERE id_word = p_id_word) THEN
        -- Delete associations in Word_in_sets
        DELETE FROM Word_in_sets
        WHERE id_word = p_id_word;

        -- Delete the word from Words
        DELETE FROM Words
        WHERE id_word = p_id_word;
    ELSE
        RAISE EXCEPTION 'Word with id % does not exist.', p_id_word;
    END IF;
END;
$$;
 L   DROP FUNCTION public.remove_word_and_associations_by_id(p_id_word integer);
       public          postgres    false    4            �            1255    16441    update_word_count()    FUNCTION       CREATE FUNCTION public.update_word_count() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Update the old set's word_count
    IF OLD.id_set IS NOT NULL THEN
        UPDATE Sets
        SET word_count = (SELECT COUNT(*) FROM Word_in_sets WHERE id_set = OLD.id_set)
        WHERE id_set = OLD.id_set;
    END IF;

    -- Update the new set's word_count
    UPDATE Sets
    SET word_count = (SELECT COUNT(*) FROM Word_in_sets WHERE id_set = NEW.id_set)
    WHERE id_set = NEW.id_set;

    RETURN NEW;
END;
$$;
 *   DROP FUNCTION public.update_word_count();
       public          postgres    false    4            �            1259    16418    sets    TABLE     �   CREATE TABLE public.sets (
    id_set integer NOT NULL,
    name character varying(50),
    image character varying(255),
    word_count integer,
    id_author integer
);
    DROP TABLE public.sets;
       public         heap    postgres    false    4            �            1259    16417    sets_id_set_seq    SEQUENCE     �   CREATE SEQUENCE public.sets_id_set_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.sets_id_set_seq;
       public          postgres    false    4    222            �           0    0    sets_id_set_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.sets_id_set_seq OWNED BY public.sets.id_set;
          public          postgres    false    221            �            1259    16425    word_in_sets    TABLE     p   CREATE TABLE public.word_in_sets (
    id_word_set integer NOT NULL,
    id_set integer,
    id_word integer
);
     DROP TABLE public.word_in_sets;
       public         heap    postgres    false    4            �            1259    16411    words    TABLE     �   CREATE TABLE public.words (
    id_word integer NOT NULL,
    word_en character varying(50),
    word_pl character varying(50)
);
    DROP TABLE public.words;
       public         heap    postgres    false    4            �            1259    16539    sets_with_words    VIEW     �  CREATE VIEW public.sets_with_words AS
 SELECT s.id_set,
    s.name AS set_name,
    s.image AS set_image,
    s.word_count,
    s.id_author,
    COALESCE(string_agg((((w.word_en)::text || ' ; '::text) || (w.word_pl)::text), ', '::text), ''::text) AS paired_words
   FROM ((public.sets s
     LEFT JOIN public.word_in_sets ws ON ((s.id_set = ws.id_set)))
     LEFT JOIN public.words w ON ((ws.id_word = w.id_word)))
  GROUP BY s.id_set, s.name, s.image, s.word_count, s.id_author
  ORDER BY s.id_set;
 "   DROP VIEW public.sets_with_words;
       public          postgres    false    220    220    220    222    222    222    222    222    224    224    4            �            1259    16452    user_details    TABLE     �   CREATE TABLE public.user_details (
    id_user_detail integer NOT NULL,
    firstname character varying(50),
    lastname character varying(50),
    phone character varying(20)
);
     DROP TABLE public.user_details;
       public         heap    postgres    false    4            �            1259    16451    user_details_id_user_detail_seq    SEQUENCE     �   CREATE SEQUENCE public.user_details_id_user_detail_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE public.user_details_id_user_detail_seq;
       public          postgres    false    226    4            �           0    0    user_details_id_user_detail_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE public.user_details_id_user_detail_seq OWNED BY public.user_details.id_user_detail;
          public          postgres    false    225            �            1259    16507    user_history    TABLE     �   CREATE TABLE public.user_history (
    id_history integer NOT NULL,
    id_user integer,
    id_set integer,
    "timestamp" timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
     DROP TABLE public.user_history;
       public         heap    postgres    false    4            �            1259    16506    user_history_id_history_seq    SEQUENCE     �   CREATE SEQUENCE public.user_history_id_history_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE public.user_history_id_history_seq;
       public          postgres    false    4    229            �           0    0    user_history_id_history_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE public.user_history_id_history_seq OWNED BY public.user_history.id_history;
          public          postgres    false    228            �            1259    16390 	   user_type    TABLE     n   CREATE TABLE public.user_type (
    id_user_type integer NOT NULL,
    type character varying(50) NOT NULL
);
    DROP TABLE public.user_type;
       public         heap    postgres    false    4            �            1259    16397    users    TABLE       CREATE TABLE public.users (
    id_user integer NOT NULL,
    id_user_type integer,
    login character varying(50),
    password character varying(255),
    email character varying(255),
    profile_picture character varying(50),
    id_user_detail integer
);
    DROP TABLE public.users;
       public         heap    postgres    false    4            �            1259    16477    user_information    VIEW     t  CREATE VIEW public.user_information AS
 SELECT u.id_user,
    u.login,
    u.password,
    u.email,
    u.profile_picture,
    ud.firstname,
    ud.lastname,
    ud.phone,
    ut.type AS user_type
   FROM ((public.users u
     JOIN public.user_details ud ON ((u.id_user_detail = ud.id_user_detail)))
     JOIN public.user_type ut ON ((u.id_user_type = ut.id_user_type)));
 #   DROP VIEW public.user_information;
       public          postgres    false    218    226    226    218    218    216    218    216    226    218    218    218    226    4            �            1259    16389    user_type_id_user_type_seq    SEQUENCE     �   CREATE SEQUENCE public.user_type_id_user_type_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public.user_type_id_user_type_seq;
       public          postgres    false    216    4            �           0    0    user_type_id_user_type_seq    SEQUENCE OWNED BY     Y   ALTER SEQUENCE public.user_type_id_user_type_seq OWNED BY public.user_type.id_user_type;
          public          postgres    false    215            �            1259    16396    users_id_user_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_user_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.users_id_user_seq;
       public          postgres    false    218    4            �           0    0    users_id_user_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.users_id_user_seq OWNED BY public.users.id_user;
          public          postgres    false    217            �            1259    16424    word_in_sets_id_word_set_seq    SEQUENCE     �   CREATE SEQUENCE public.word_in_sets_id_word_set_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE public.word_in_sets_id_word_set_seq;
       public          postgres    false    224    4            �           0    0    word_in_sets_id_word_set_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE public.word_in_sets_id_word_set_seq OWNED BY public.word_in_sets.id_word_set;
          public          postgres    false    223            �            1259    16410    words_id_word_seq    SEQUENCE     �   CREATE SEQUENCE public.words_id_word_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.words_id_word_seq;
       public          postgres    false    4    220            �           0    0    words_id_word_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.words_id_word_seq OWNED BY public.words.id_word;
          public          postgres    false    219            �           2604    16421    sets id_set    DEFAULT     j   ALTER TABLE ONLY public.sets ALTER COLUMN id_set SET DEFAULT nextval('public.sets_id_set_seq'::regclass);
 :   ALTER TABLE public.sets ALTER COLUMN id_set DROP DEFAULT;
       public          postgres    false    221    222    222            �           2604    16455    user_details id_user_detail    DEFAULT     �   ALTER TABLE ONLY public.user_details ALTER COLUMN id_user_detail SET DEFAULT nextval('public.user_details_id_user_detail_seq'::regclass);
 J   ALTER TABLE public.user_details ALTER COLUMN id_user_detail DROP DEFAULT;
       public          postgres    false    226    225    226            �           2604    16510    user_history id_history    DEFAULT     �   ALTER TABLE ONLY public.user_history ALTER COLUMN id_history SET DEFAULT nextval('public.user_history_id_history_seq'::regclass);
 F   ALTER TABLE public.user_history ALTER COLUMN id_history DROP DEFAULT;
       public          postgres    false    228    229    229            �           2604    16393    user_type id_user_type    DEFAULT     �   ALTER TABLE ONLY public.user_type ALTER COLUMN id_user_type SET DEFAULT nextval('public.user_type_id_user_type_seq'::regclass);
 E   ALTER TABLE public.user_type ALTER COLUMN id_user_type DROP DEFAULT;
       public          postgres    false    216    215    216            �           2604    16400    users id_user    DEFAULT     n   ALTER TABLE ONLY public.users ALTER COLUMN id_user SET DEFAULT nextval('public.users_id_user_seq'::regclass);
 <   ALTER TABLE public.users ALTER COLUMN id_user DROP DEFAULT;
       public          postgres    false    218    217    218            �           2604    16428    word_in_sets id_word_set    DEFAULT     �   ALTER TABLE ONLY public.word_in_sets ALTER COLUMN id_word_set SET DEFAULT nextval('public.word_in_sets_id_word_set_seq'::regclass);
 G   ALTER TABLE public.word_in_sets ALTER COLUMN id_word_set DROP DEFAULT;
       public          postgres    false    223    224    224            �           2604    16414    words id_word    DEFAULT     n   ALTER TABLE ONLY public.words ALTER COLUMN id_word SET DEFAULT nextval('public.words_id_word_seq'::regclass);
 <   ALTER TABLE public.words ALTER COLUMN id_word DROP DEFAULT;
       public          postgres    false    220    219    220            v          0    16418    sets 
   TABLE DATA           J   COPY public.sets (id_set, name, image, word_count, id_author) FROM stdin;
    public          postgres    false    222   Zp       z          0    16452    user_details 
   TABLE DATA           R   COPY public.user_details (id_user_detail, firstname, lastname, phone) FROM stdin;
    public          postgres    false    226   �p       |          0    16507    user_history 
   TABLE DATA           P   COPY public.user_history (id_history, id_user, id_set, "timestamp") FROM stdin;
    public          postgres    false    229   q       p          0    16390 	   user_type 
   TABLE DATA           7   COPY public.user_type (id_user_type, type) FROM stdin;
    public          postgres    false    216   >u       r          0    16397    users 
   TABLE DATA           o   COPY public.users (id_user, id_user_type, login, password, email, profile_picture, id_user_detail) FROM stdin;
    public          postgres    false    218   ju       x          0    16425    word_in_sets 
   TABLE DATA           D   COPY public.word_in_sets (id_word_set, id_set, id_word) FROM stdin;
    public          postgres    false    224   �v       t          0    16411    words 
   TABLE DATA           :   COPY public.words (id_word, word_en, word_pl) FROM stdin;
    public          postgres    false    220   �v       �           0    0    sets_id_set_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.sets_id_set_seq', 79, true);
          public          postgres    false    221            �           0    0    user_details_id_user_detail_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('public.user_details_id_user_detail_seq', 6, true);
          public          postgres    false    225            �           0    0    user_history_id_history_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('public.user_history_id_history_seq', 184, true);
          public          postgres    false    228            �           0    0    user_type_id_user_type_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.user_type_id_user_type_seq', 2, true);
          public          postgres    false    215            �           0    0    users_id_user_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.users_id_user_seq', 19, true);
          public          postgres    false    217            �           0    0    word_in_sets_id_word_set_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('public.word_in_sets_id_word_set_seq', 27, true);
          public          postgres    false    223            �           0    0    words_id_word_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.words_id_word_seq', 28, true);
          public          postgres    false    219            �           2606    16423    sets sets_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.sets
    ADD CONSTRAINT sets_pkey PRIMARY KEY (id_set);
 8   ALTER TABLE ONLY public.sets DROP CONSTRAINT sets_pkey;
       public            postgres    false    222            �           2606    16446    sets unique_set_name_constraint 
   CONSTRAINT     Z   ALTER TABLE ONLY public.sets
    ADD CONSTRAINT unique_set_name_constraint UNIQUE (name);
 I   ALTER TABLE ONLY public.sets DROP CONSTRAINT unique_set_name_constraint;
       public            postgres    false    222            �           2606    16466 ,   user_details unique_user_details_combination 
   CONSTRAINT     }   ALTER TABLE ONLY public.user_details
    ADD CONSTRAINT unique_user_details_combination UNIQUE (firstname, lastname, phone);
 V   ALTER TABLE ONLY public.user_details DROP CONSTRAINT unique_user_details_combination;
       public            postgres    false    226    226    226            �           2606    16450 "   users unique_user_email_constraint 
   CONSTRAINT     ^   ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_user_email_constraint UNIQUE (email);
 L   ALTER TABLE ONLY public.users DROP CONSTRAINT unique_user_email_constraint;
       public            postgres    false    218            �           2606    16448 "   users unique_user_login_constraint 
   CONSTRAINT     ^   ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_user_login_constraint UNIQUE (login);
 L   ALTER TABLE ONLY public.users DROP CONSTRAINT unique_user_login_constraint;
       public            postgres    false    218            �           2606    16444 %   user_type unique_user_type_constraint 
   CONSTRAINT     `   ALTER TABLE ONLY public.user_type
    ADD CONSTRAINT unique_user_type_constraint UNIQUE (type);
 O   ALTER TABLE ONLY public.user_type DROP CONSTRAINT unique_user_type_constraint;
       public            postgres    false    216            �           2606    16660    words unique_word_combination 
   CONSTRAINT     d   ALTER TABLE ONLY public.words
    ADD CONSTRAINT unique_word_combination UNIQUE (word_en, word_pl);
 G   ALTER TABLE ONLY public.words DROP CONSTRAINT unique_word_combination;
       public            postgres    false    220    220            �           2606    16664 (   word_in_sets unique_word_set_combination 
   CONSTRAINT     n   ALTER TABLE ONLY public.word_in_sets
    ADD CONSTRAINT unique_word_set_combination UNIQUE (id_set, id_word);
 R   ALTER TABLE ONLY public.word_in_sets DROP CONSTRAINT unique_word_set_combination;
       public            postgres    false    224    224            �           2606    16457    user_details user_details_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.user_details
    ADD CONSTRAINT user_details_pkey PRIMARY KEY (id_user_detail);
 H   ALTER TABLE ONLY public.user_details DROP CONSTRAINT user_details_pkey;
       public            postgres    false    226            �           2606    16513    user_history user_history_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY public.user_history
    ADD CONSTRAINT user_history_pkey PRIMARY KEY (id_history);
 H   ALTER TABLE ONLY public.user_history DROP CONSTRAINT user_history_pkey;
       public            postgres    false    229            �           2606    16395    user_type user_type_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY public.user_type
    ADD CONSTRAINT user_type_pkey PRIMARY KEY (id_user_type);
 B   ALTER TABLE ONLY public.user_type DROP CONSTRAINT user_type_pkey;
       public            postgres    false    216            �           2606    16459    users users_id_user_detail_key 
   CONSTRAINT     c   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_user_detail_key UNIQUE (id_user_detail);
 H   ALTER TABLE ONLY public.users DROP CONSTRAINT users_id_user_detail_key;
       public            postgres    false    218            �           2606    16404    users users_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id_user);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    218            �           2606    16430    word_in_sets word_in_sets_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY public.word_in_sets
    ADD CONSTRAINT word_in_sets_pkey PRIMARY KEY (id_word_set);
 H   ALTER TABLE ONLY public.word_in_sets DROP CONSTRAINT word_in_sets_pkey;
       public            postgres    false    224            �           2606    16416    words words_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY public.words
    ADD CONSTRAINT words_pkey PRIMARY KEY (id_word);
 :   ALTER TABLE ONLY public.words DROP CONSTRAINT words_pkey;
       public            postgres    false    220            �           2620    16442 !   word_in_sets word_in_sets_trigger    TRIGGER     �   CREATE TRIGGER word_in_sets_trigger AFTER INSERT OR DELETE OR UPDATE ON public.word_in_sets FOR EACH ROW EXECUTE FUNCTION public.update_word_count();
 :   DROP TRIGGER word_in_sets_trigger ON public.word_in_sets;
       public          postgres    false    224    231            �           2606    16472    sets sets_id_author_fkey    FK CONSTRAINT     ~   ALTER TABLE ONLY public.sets
    ADD CONSTRAINT sets_id_author_fkey FOREIGN KEY (id_author) REFERENCES public.users(id_user);
 B   ALTER TABLE ONLY public.sets DROP CONSTRAINT sets_id_author_fkey;
       public          postgres    false    3267    218    222            �           2606    16519 %   user_history user_history_id_set_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_history
    ADD CONSTRAINT user_history_id_set_fkey FOREIGN KEY (id_set) REFERENCES public.sets(id_set);
 O   ALTER TABLE ONLY public.user_history DROP CONSTRAINT user_history_id_set_fkey;
       public          postgres    false    229    3273    222            �           2606    16514 &   user_history user_history_id_user_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_history
    ADD CONSTRAINT user_history_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.users(id_user);
 P   ALTER TABLE ONLY public.user_history DROP CONSTRAINT user_history_id_user_fkey;
       public          postgres    false    3267    229    218            �           2606    16460    users users_id_user_detail_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_user_detail_fkey FOREIGN KEY (id_user_detail) REFERENCES public.user_details(id_user_detail);
 I   ALTER TABLE ONLY public.users DROP CONSTRAINT users_id_user_detail_fkey;
       public          postgres    false    226    3283    218            �           2606    16405    users users_id_user_type_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_user_type_fkey FOREIGN KEY (id_user_type) REFERENCES public.user_type(id_user_type);
 G   ALTER TABLE ONLY public.users DROP CONSTRAINT users_id_user_type_fkey;
       public          postgres    false    218    216    3259            �           2606    16431 %   word_in_sets word_in_sets_id_set_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.word_in_sets
    ADD CONSTRAINT word_in_sets_id_set_fkey FOREIGN KEY (id_set) REFERENCES public.sets(id_set);
 O   ALTER TABLE ONLY public.word_in_sets DROP CONSTRAINT word_in_sets_id_set_fkey;
       public          postgres    false    3273    222    224            �           2606    16436 &   word_in_sets word_in_sets_id_word_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.word_in_sets
    ADD CONSTRAINT word_in_sets_id_word_fkey FOREIGN KEY (id_word) REFERENCES public.words(id_word);
 P   ALTER TABLE ONLY public.word_in_sets DROP CONSTRAINT word_in_sets_id_word_fkey;
       public          postgres    false    3271    224    220            v   d   x�3�����,�/�̅�zy�&��\��)Ŝ�`��P ��I�$�T�$h��X��4,1=�3#%!g��Z���X�e�%A�1z\\\ 2+&�      z   8   x�3�,1"06�2�LL��̃��F�&�f��\��%%F0�e�Y�Z\� �b���� �{      |     x�u�Y�$GD��O���}�����1�!�t2��J<��F�R�������o��ч��TH�B�ܪ��	���Q���az�o5Y)����f�1�A��`޷da�*wR��/�+�J������|K��BTƿ0��ܩL���5o�7�TK��EBH�+b2Y��{�.Aq0�Fl�JP&��We��q`W�ّU�Z	4n(�C�ҕ�G����|�6F�T+����g��x=�7�"K~"��c��kEb�r�#��
ئ�9%����7$o:Y��ţ0���|T>~k)C�OD��S'VCFj���%0��D�r�Yx(Vħy����YF9��hL?Z�7�!��|����B�.[�R�~=�ߐ��Q��m_D�R�C8[���s'22���!fC��o�Hy-X���0�R�C����]�gI��O�����+R3?»H��7$a(>���L�l�C||�n:S�@��]�~4o�f��U��Q D��%�!y]I����|�6���2��1�Ћ��">�*���X:�E`����,K�;�`��>�JD1�$�%V�4P�K�QX��JD�Ma˪,Im?��2Ą��}rmF p�V�m��)�#��˒�98�N)�����W�	�\VċF�����ODzm���pK.¡�fgA[tH�\�E�\ko�W�>@;�
&!�C��"�����+���m/�������b>��ԛ�Ԛ� ~1���O-}�#.��&:U�C99��^<��2���
�ӫ,?U��Y ��b��Ka�^�f[����yGڬ��=G��%ܘed�g�-�i�7�U�h4J8Yi	�e�/��l\�L��������r(�0��I�%̴�2�����<���?6(��掝4ה���k���t�2�Q^st�����Y�ct��e��>���,���UQ��׼��m��؜�k̆�����v��N�Y��C>�YqZ��!��J����c�{���@�XΙz����9�c����=CZ�oH,��h,��<�XN����A��s�9�bs�      p      x�3�LL����2�,-N-����� : �      r     x�5ν��0 �:y�D��!( �2���pGN@ ��?o��n��,� 
$�<`\{��ʚ��kr���{�5t�y��M��R��j+M��{�G9z�uIЏ��qƐ`@@��Eӳ���8tT̅�˔eGO��[�b���s��p5L�u�ƛ$<�X���{�@2�K���W@Q]6mZ1�9K�_Kc���Ž�V���2��n~J���U���,�dֱ�&{7�bAwe�ɼ
O�K�߃z���n����:�o��y�ت}��^��A� �k      x   )   x�3�4�4�2��\F@҈�Hs�rq�q��qqq U��      t   �   x��A
�0 ���+��*~@ū�W/k�蒘�$Eܣ �A�N�/�u`��r�0���`¡7�~��l5hr��ӫ~�B��8�
�>1G��x�7���>Qq;��F
pLƾ�K$l6��	>�C��i��~�.�     